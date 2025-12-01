<?php

namespace App\Repositories;

use App\DTOs\TicketData;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Services\DBConnectService;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository for ticket data access operations.
 * Implements the Repository pattern for clean architecture.
 */
class TicketRepository implements TicketRepositoryInterface
{
    public function __construct(
        private readonly DBConnectService $databaseService
    ) {}

    /**
     * Create a new ticket in the appropriate database.
     */
    public function create(TicketData $ticketData): Ticket
    {
        $connection = $ticketData->getDatabaseConnection();

        $ticket = new Ticket();
        $ticket->setConnection($connection);
        $ticket->fill($ticketData->toArray());
        $ticket->save();

        return $ticket;
    }

    /**
     * Find a ticket by ID in a specific database.
     */
    public function find(int $id, string $connection): ?Ticket
    {
        return Ticket::on($connection)->find($id);
    }

    /**
     * Get all tickets from a specific database connection.
     *
     * @return Collection<int, Ticket>
     */
    public function getAll(string $connection): Collection
    {
        return Ticket::on($connection)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all tickets from all department databases.
     * Merges results and sorts by creation date.
     *
     * @return Collection<int, Ticket>
     */
    public function getAllFromAllDatabases(): Collection
    {
        $allTickets = new Collection();

        foreach ($this->databaseService->getAllConnections() as $connection) {
            try {
                $tickets = $this->getAll($connection);
                $allTickets = $allTickets->merge($tickets);
            } catch (\Exception $e) {
                \Log::error("Failed to fetch tickets from {$connection}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $allTickets->sortByDesc('created_at');
    }

    /**
     * Get tickets filtered by status from a specific database.
     *
     * @return Collection<int, Ticket>
     */
    public function getByStatus(TicketStatus $status, string $connection): Collection
    {
        return Ticket::on($connection)
            ->where('status', $status->value)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Update a ticket in a specific database.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, string $connection, array $data): bool
    {
        $ticket = $this->find($id, $connection);

        if (! $ticket) {
            return false;
        }

        return $ticket->update($data);
    }

    /**
     * Delete a ticket from a specific database.
     */
    public function delete(int $id, string $connection): bool
    {
        $ticket = $this->find($id, $connection);

        if (! $ticket) {
            return false;
        }

        return (bool) $ticket->delete();
    }
}
