<?php

namespace Tests\Unit;

use App\DTOs\TicketData;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use Tests\TestCase;

class TicketDataTest extends TestCase
{
    public function test_can_create_ticket_data_from_request(): void
    {
        $requestData = [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'subject' => 'Billing Question',
            'description' => 'I have a question about my recent invoice.',
            'type' => 'Account & Billing',
        ];

        $ticketData = TicketData::fromRequest($requestData);

        $this->assertEquals('Jane Smith', $ticketData->name);
        $this->assertEquals('jane@example.com', $ticketData->email);
        $this->assertEquals(TicketType::ACCOUNT_BILLING, $ticketData->type);
        $this->assertEquals(TicketStatus::OPEN, $ticketData->status);
    }

    public function test_can_convert_ticket_data_to_array(): void
    {
        $ticketData = new TicketData(
            name: 'Test User',
            email: 'test@example.com',
            subject: 'Test',
            description: 'Test description',
            type: TicketType::TECHNICAL_ISSUES,
            status: TicketStatus::OPEN,
        );

        $array = $ticketData->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('Test User', $array['name']);
        $this->assertEquals('Technical Issues', $array['type']);
        $this->assertEquals('open', $array['status']);
    }

    public function test_get_database_connection_returns_correct_connection(): void
    {
        $ticketData = new TicketData(
            name: 'Test',
            email: 'test@example.com',
            subject: 'Test',
            description: 'Test',
            type: TicketType::PRODUCT_SERVICE,
        );

        $connection = $ticketData->getDatabaseConnection();

        $this->assertEquals('product_department', $connection);
    }
}