<?php

namespace Tests\Unit;

use App\DTOs\TicketData;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Services\DBConnectService;
use App\Services\TicketService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class TicketServiceTest extends TestCase
{
    public function test_create_ticket_saves_to_correct_database(): void
    {
        $ticketData = new TicketData(
            name: 'John Doe',
            email: 'john@example.com',
            subject: 'Test Subject',
            description: 'Test Description',
            type: TicketType::TECHNICAL_ISSUES,
        );

        $repository = $this->app->make(TicketRepositoryInterface::class);
        $databaseService = $this->app->make(DBConnectService::class);
        $service = new TicketService($repository, $databaseService);

        $ticket = $service->createTicket($ticketData);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals('John Doe', $ticket->name);
        $this->assertEquals(TicketStatus::OPEN->value, $ticket->status);
        $this->assertEquals('technical_department', $ticket->getConnectionName());

        // Verify ticket exists in correct database
        $this->assertDatabaseHas('tickets', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ], 'technical_department');
    }

    public function test_create_ticket_in_different_databases(): void
    {
        $repository = $this->app->make(TicketRepositoryInterface::class);
        $databaseService = $this->app->make(DBConnectService::class);
        $service = new TicketService($repository, $databaseService);

        $testCases = [
            [
                'type' => TicketType::TECHNICAL_ISSUES,
                'connection' => 'technical_department',
            ],
            [
                'type' => TicketType::ACCOUNT_BILLING,
                'connection' => 'account_department',
            ],
            [
                'type' => TicketType::PRODUCT_SERVICE,
                'connection' => 'product_department',
            ],
        ];

        foreach ($testCases as $testCase) {
            $ticketData = new TicketData(
                name: 'Test User',
                email: 'test@example.com',
                subject: 'Test Subject',
                description: 'Test Description',
                type: $testCase['type'],
            );

            $ticket = $service->createTicket($ticketData);

            // Verify correct database connection
            $this->assertEquals($testCase['connection'], $ticket->getConnectionName());

            // Verify ticket exists in correct database - use string values
            $this->assertDatabaseHas('tickets', [
                'name' => 'Test User',
                'type' => $testCase['type']->value, // Use enum value
            ], $testCase['connection']);
        }
    }

    public function test_get_all_tickets_returns_collection(): void
    {
        $repository = $this->app->make(TicketRepositoryInterface::class);
        $databaseService = $this->app->make(DBConnectService::class);
        $service = new TicketService($repository, $databaseService);

        // Create tickets in different databases
        $types = [
            TicketType::TECHNICAL_ISSUES,
            TicketType::ACCOUNT_BILLING,
            TicketType::PRODUCT_SERVICE,
        ];

        foreach ($types as $type) {
            $ticketData = new TicketData(
                name: 'Test User',
                email: 'test@example.com',
                subject: 'Test Subject',
                description: 'Test Description',
                type: $type,
            );

            $service->createTicket($ticketData);
        }

        $tickets = $service->getAllTickets();

        $this->assertInstanceOf(Collection::class, $tickets);
        $this->assertGreaterThanOrEqual(3, $tickets->count());
    }

    public function test_find_ticket_calls_repository(): void
    {
        $repository = $this->app->make(TicketRepositoryInterface::class);
        $databaseService = $this->app->make(DBConnectService::class);
        $service = new TicketService($repository, $databaseService);

        // Create a real ticket
        $ticketData = new TicketData(
            name: 'Test User',
            email: 'test@example.com',
            subject: 'Test Subject',
            description: 'Test Description',
            type: TicketType::TECHNICAL_ISSUES,
        );

        $createdTicket = $service->createTicket($ticketData);

        // Find the ticket
        $result = $service->findTicket($createdTicket->id, 'technical_department');

        $this->assertInstanceOf(Ticket::class, $result);
        $this->assertEquals($createdTicket->id, $result->id);
        $this->assertEquals('technical_department', $result->getConnectionName());
    }

    public function test_find_ticket_across_all_databases(): void
    {
        $repository = $this->app->make(TicketRepositoryInterface::class);
        $databaseService = $this->app->make(DBConnectService::class);
        $service = new TicketService($repository, $databaseService);

        // Create ticket in billing database
        $ticketData = new TicketData(
            name: 'Test User',
            email: 'test@example.com',
            subject: 'Billing Issue',
            description: 'Test Description',
            type: TicketType::ACCOUNT_BILLING,
        );

        $createdTicket = $service->createTicket($ticketData);

        // Find ticket across all databases
        $result = $service->findTicketAcrossAllDatabases($createdTicket->id);

        $this->assertInstanceOf(Ticket::class, $result);
        $this->assertEquals($createdTicket->id, $result->id);
        $this->assertEquals('account_department', $result->getConnectionName());
    }
}