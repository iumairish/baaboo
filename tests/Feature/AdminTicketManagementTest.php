<?php

namespace Tests\Feature;

use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Models\Admin;
use App\Models\Ticket;
use Tests\TestCase;

class AdminTicketManagementTest extends TestCase
{
    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    public function test_admin_can_view_all_tickets(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Create tickets in different databases with correct connections
        foreach (TicketType::cases() as $type) {
            $connection = $type->getDatabaseConnection();
            
            for ($i = 0; $i < 2; $i++) {
                $this->createTicketInDatabase($connection, [
                    'type' => $type->value,
                ]);
            }
        }

        $response = $this->get(route('admin.tickets.index'));

        $response->assertStatus(200);
        $response->assertSee('All Support Tickets');
    }

    public function test_admin_can_view_single_ticket(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Create ticket in correct database
        $ticket = $this->createTicketInDatabase('technical_department', [
            'type' => TicketType::TECHNICAL_ISSUES->value,
        ]);

        $response = $this->get(route('admin.tickets.show', [
            'id' => $ticket->id,
            'connection' => 'technical_department',
        ]));

        $response->assertStatus(200);
        $response->assertSee($ticket->subject);
        $response->assertSee($ticket->name);
    }

    public function test_admin_can_add_note_to_ticket(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Create ticket in correct database
        $ticket = $this->createTicketInDatabase('technical_department', [
            'type' => TicketType::TECHNICAL_ISSUES->value,
            'status' => TicketStatus::OPEN->value,
        ]);

        $noteContent = '<p>This is a test note with important information.</p>';

        $response = $this->post(route('admin.tickets.add-note', $ticket->id), [
            'connection' => 'technical_department',
            'note' => $noteContent,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ticket_notes', [
            'ticket_id' => $ticket->id,
        ], 'technical_department');

        // Verify ticket status was updated in correct database
        $updatedTicket = Ticket::on('technical_department')->find($ticket->id);
        $this->assertEquals(TicketStatus::NOTED->value, $updatedTicket->status);
    }

    public function test_note_validation_requires_minimum_length(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Create ticket in correct database
        $ticket = $this->createTicketInDatabase('technical_department', [
            'type' => TicketType::TECHNICAL_ISSUES->value,
        ]);

        $response = $this->post(route('admin.tickets.add-note', $ticket->id), [
            'connection' => 'technical_department',
            'note' => 'Short', // Too short
        ]);

        $response->assertSessionHasErrors(['note']);
    }
}