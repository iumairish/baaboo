<?php

namespace App\Services;

use App\Enums\TicketType;
use InvalidArgumentException;

/**
 * Service for managing multi-database connections.
 * Handles routing to appropriate department databases.
 */
class DBConnectService
{
    /**
     * Get the database connection name for a ticket type.
     *
     * @throws InvalidArgumentException
     */
    public function getConnectionForType(string|TicketType $type): string
    {
        $ticketType = is_string($type) ? TicketType::from($type) : $type;
        
        return $ticketType->getDatabaseConnection();
    }

    /**
     * Get all department database connections.
     *
     * @return array<string>
     */
    public function getAllConnections(): array
    {
        return array_map(
            fn(TicketType $type) => $type->getDatabaseConnection(),
            TicketType::cases()
        );
    }

    /**
     * Verify all department databases are accessible.
     *
     * @return array<string, bool>
     */
    public function verifyConnections(): array
    {
        $results = [];
        
        foreach ($this->getAllConnections() as $connection) {
            try {
                \DB::connection($connection)->getPdo();
                $results[$connection] = true;
            } catch (\Exception $e) {
                $results[$connection] = false;
                \Log::error("Database connection failed: {$connection}", [
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $results;
    }
}