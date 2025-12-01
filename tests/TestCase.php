<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Disable CSRF for tests
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        
        // Clean test data before each test
        $this->cleanupTestData();
    }

    /**
     * Clean up test data after each test
     */
    protected function tearDown(): void
    {
        // Clean test data after test finishes
        $this->cleanupTestData();
        
        parent::tearDown();
    }

    /**
     * Clean up test data from all databases
     */
    protected function cleanupTestData(): void
    {
        $connections = [
            'technical_department',
            'account_department',
            'product_department',
            'general_department',
            'feedback_department',
        ];

        foreach ($connections as $connection) {
            try {
                // Delete test tickets (created in last hour)
                DB::connection($connection)
                    ->table('ticket_notes')
                    ->where('created_at', '>=', now()->subHour())
                    ->delete();
                    
                DB::connection($connection)
                    ->table('tickets')
                    ->where('created_at', '>=', now()->subHour())
                    ->delete();
            } catch (\Exception $e) {
                // Ignore if table doesn't exist
            }
        }
        
        // Clean test admins (keep the seeded admin)
        try {
            DB::connection('mysql')
                ->table('admins')
                ->where('email', '!=', 'admin@example.com')
                ->where('created_at', '>=', now()->subHour())
                ->delete();
        } catch (\Exception $e) {
            // Ignore
        }
    }

    /**
     * Helper method to create a ticket in a specific database
     */
    protected function createTicketInDatabase(string $connection, array $attributes = []): \App\Models\Ticket
    {
        $ticket = \App\Models\Ticket::factory()->make($attributes);
        $ticket->setConnection($connection);
        $ticket->save();
        
        return $ticket;
    }
}