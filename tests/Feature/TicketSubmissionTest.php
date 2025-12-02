<?php

namespace Tests\Feature;

use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Models\Ticket;
use Tests\TestCase;

class TicketSubmissionTest extends TestCase
{
    public function test_customer_can_view_ticket_form(): void
    {
        $response = $this->get(route('tickets.create'));

        $response->assertStatus(200);
        $response->assertSee('Submit a Support Ticket');
        $response->assertSee('Technical Issues');
        $response->assertSee('Account & Billing');
    }

    public function test_customer_can_submit_valid_ticket(): void
    {
        $ticketData = [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'subject' => 'Need help with login',
            'description' => 'I cannot login to my account. Please help me with this issue.',
            'type' => 'Technical Issues',
        ];

        $response = $this->post(route('tickets.store'), $ticketData);

        $response->assertRedirect(route('tickets.create'));
        $response->assertSessionHas('success');

        // Verify ticket exists in technical_issues database
        $ticket = Ticket::on('technical_department')
            ->where('name', 'John Doe')
            ->where('email', 'john@gmail.com')
            ->first();

        $this->assertNotNull($ticket, 'Ticket should exist in database');
        $this->assertEquals('John Doe', $ticket->name);
        $this->assertEquals('john@gmail.com', $ticket->email);
        $this->assertEquals('Need help with login', $ticket->subject);
        $this->assertEquals('Technical Issues', $ticket->type);
        $this->assertEquals('open', $ticket->status);
    }

    public function test_ticket_is_saved_to_correct_database_connection(): void
    {
        $testCases = [
            [
                'type' => 'Technical Issues',
                'connection' => 'technical_department',
            ],
            [
                'type' => 'Account & Billing',
                'connection' => 'account_department',
            ],
            [
                'type' => 'Product & Service',
                'connection' => 'product_department',
            ],
        ];

        foreach ($testCases as $testCase) {
            $ticketData = [
                'name' => 'Test User ' . $testCase['type'],
                'email' => 'test@gmail.com',
                'subject' => 'Test Subject',
                'description' => 'Test description for ticket that is long enough.',
                'type' => $testCase['type'],
            ];

            $response = $this->post(route('tickets.store'), $ticketData);
            
            $response->assertRedirect(route('tickets.create'));

            // Check in the correct database connection
            $ticket = Ticket::on($testCase['connection'])
                ->where('name', 'Test User ' . $testCase['type'])
                ->latest()
                ->first();
            
            $this->assertNotNull($ticket, "Ticket should exist in {$testCase['connection']}");
            $this->assertEquals($testCase['type'], $ticket->type);
            $this->assertEquals($testCase['connection'], $ticket->getConnectionName());
        }
    }

    public function test_validation_errors_are_shown_for_invalid_data(): void
    {
        $response = $this->post(route('tickets.store'), [
            'name' => '', // Empty name
            'email' => 'invalid-email', // Invalid email
            'subject' => 'Hi', // Too short
            'description' => 'Short', // Too short
            'type' => 'Invalid Type', // Invalid type
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'description', 'type']);
    }

    public function test_ticket_requires_all_mandatory_fields(): void
    {
        $response = $this->post(route('tickets.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'subject',
            'description',
            'type',
        ]);
    }

    public function test_ticket_description_must_be_minimum_length(): void
    {
        $response = $this->post(route('tickets.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'description' => 'Short', // Less than 10 characters
            'type' => 'Technical Issues',
        ]);

        $response->assertSessionHasErrors(['description']);
    }
}