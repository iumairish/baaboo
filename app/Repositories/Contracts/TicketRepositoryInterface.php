<?php

namespace App\Repositories\Contracts;

use App\DTOs\TicketData;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

interface TicketRepositoryInterface
{
    /**
     * Create a new ticket.
     */
    public function create(TicketData $ticketData): Ticket;

    /**
     * Find a ticket by ID.
     */
    public function find(int $id, string $connection): ?Ticket;

    /**
     * Get all tickets from a specific database.
     *
     * @return Collection<int, Ticket>
     */
    public function getAll(string $connection): Collection;

    /**
     * Get all tickets from all department databases.
     *
     * @return Collection<int, Ticket>
     */
    public function getAllFromAllDatabases(): Collection;

    /**
     * Get tickets by status.
     *
     * @return Collection<int, Ticket>
     */
    public function getByStatus(TicketStatus $status, string $connection): Collection;

    /**
     * Update a ticket.
     */
    public function update(int $id, string $connection, array $data): bool;

    /**
     * Delete a ticket.
     */
    public function delete(int $id, string $connection): bool;
}
