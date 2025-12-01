<?php

namespace App\Services;

use App\DTOs\TicketData;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketNote;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling ticket business logic.
 * Orchestrates operations across repositories and models.
 */
class TicketService
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly DBConnectService $databaseService
    ) {}

    /**
     * Create a new ticket in the appropriate department database.
     *
     * @throws \Exception
     */
    public function createTicket(TicketData $ticketData): Ticket
    {
        $connection = $ticketData->getDatabaseConnection();

        try {
            DB::connection($connection)->beginTransaction();

            $ticket = $this->ticketRepository->create($ticketData);

            DB::connection($connection)->commit();

            Log::info('Ticket created successfully', [
                'ticket_id' => $ticket->id,
                'type' => $ticketData->type->value,
                'connection' => $connection,
            ]);

            return $ticket;
        } catch (\Exception $e) {
            DB::connection($connection)->rollBack();

            Log::error('Failed to create ticket', [
                'error' => $e->getMessage(),
                'type' => $ticketData->type->value,
            ]);

            throw $e;
        }
    }

    /**
     * Get all tickets from all departments.
     *
     * @return Collection<int, Ticket>
     */
    public function getAllTickets(): Collection
    {
        return $this->ticketRepository->getAllFromAllDatabases();
    }

    /**
     * Find a ticket by ID in a specific connection.
     */
    public function findTicket(int $id, string $connection): ?Ticket
    {
        return $this->ticketRepository->find($id, $connection);
    }

    /**
     * Find a ticket across all department databases.
     */
    public function findTicketAcrossAllDatabases(int $id): ?Ticket
    {
        foreach ($this->databaseService->getAllConnections() as $connection) {
            $ticket = $this->findTicket($id, $connection);
            if ($ticket) {
                return $ticket;
            }
        }

        return null;
    }

    /**
     * Add a note to a ticket and update its status.
     *
     * @throws \Exception
     */
    public function addNoteToTicket(
        int $ticketId,
        string $connection,
        string $noteContent
    ): bool {
        try {
            DB::connection($connection)->beginTransaction();

            $ticket = $this->findTicket($ticketId, $connection);

            if (! $ticket) {
                throw new \Exception('Ticket not found');
            }

            // Create note
            $note = new TicketNote();
            $note->setConnection($connection);
            $note->ticket_id = $ticketId;
            $note->note = $noteContent;
            $note->save();

            // Update ticket status
            $ticket->status = TicketStatus::NOTED;
            $ticket->save();

            DB::connection($connection)->commit();

            Log::info('Note added to ticket', [
                'ticket_id' => $ticketId,
                'connection' => $connection,
            ]);

            return true;
        } catch (\Exception $e) {
            DB::connection($connection)->rollBack();

            Log::error('Failed to add note to ticket', [
                'ticket_id' => $ticketId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get tickets by status from a specific database.
     *
     * @return Collection<int, Ticket>
     */
    public function getTicketsByStatus(
        TicketStatus $status,
        string $connection
    ): Collection {
        return $this->ticketRepository->getByStatus($status, $connection);
    }

    /**
     * Get recent tickets from all departments.
     *
     * @return Collection<int, Ticket>
     */
    public function getRecentTickets(int $days = 30): Collection
    {
        $tickets = new Collection();

        foreach ($this->databaseService->getAllConnections() as $connection) {
            $recentTickets = Ticket::query()
                ->setConnection($connection)
                ->recent($days)
                ->get();

            $tickets = $tickets->merge($recentTickets);
        }

        return $tickets->sortByDesc('created_at');
    }
}
