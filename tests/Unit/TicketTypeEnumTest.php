<?php

namespace Tests\Unit;

use App\Enums\TicketType;
use Tests\TestCase;

class TicketTypeEnumTest extends TestCase
{
    public function test_ticket_types_have_correct_database_connections(): void
    {
        $this->assertEquals('technical_department', TicketType::TECHNICAL_ISSUES->getDatabaseConnection());
        $this->assertEquals('account_department', TicketType::ACCOUNT_BILLING->getDatabaseConnection());
        $this->assertEquals('product_department', TicketType::PRODUCT_SERVICE->getDatabaseConnection());
        $this->assertEquals('general_department', TicketType::GENERAL_INQUIRY->getDatabaseConnection());
        $this->assertEquals('feedback_department', TicketType::FEEDBACK_SUGGESTIONS->getDatabaseConnection());
    }

    public function test_to_array_returns_all_types(): void
    {
        $types = TicketType::toArray();

        $this->assertIsArray($types);
        $this->assertCount(5, $types);
        $this->assertArrayHasKey('Technical Issues', $types);
    }

    public function test_label_returns_correct_value(): void
    {
        $this->assertEquals('Technical Issues', TicketType::TECHNICAL_ISSUES->label());
        $this->assertEquals('Account & Billing', TicketType::ACCOUNT_BILLING->label());
    }
}