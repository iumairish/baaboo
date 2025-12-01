<?php

namespace Tests\Unit;

use App\Enums\TicketType;
use App\Services\DBConnectService;
use Tests\TestCase;

class DBConnectServiceTest extends TestCase
{
    private DBConnectService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DBConnectService();
    }

    public function test_get_connection_for_technical_department(): void
    {
        $connection = $this->service->getConnectionForType(TicketType::TECHNICAL_ISSUES);
        
        $this->assertEquals('technical_department', $connection);
    }

    public function test_get_connection_for_account_department(): void
    {
        $connection = $this->service->getConnectionForType(TicketType::ACCOUNT_BILLING);
        
        $this->assertEquals('account_department', $connection);
    }

    public function test_get_connection_for_string_type(): void
    {
        $connection = $this->service->getConnectionForType('Product & Service');
        
        $this->assertEquals('product_department', $connection);
    }

    public function test_get_all_connections_returns_all_department_databases(): void
    {
        $connections = $this->service->getAllConnections();
        
        $this->assertIsArray($connections);
        $this->assertCount(5, $connections);
        $this->assertContains('technical_department', $connections);
        $this->assertContains('account_department', $connections);
        $this->assertContains('product_department', $connections);
        $this->assertContains('general_department', $connections);
        $this->assertContains('feedback_department', $connections);
    }

    public function test_invalid_ticket_type_throws_exception(): void
    {
        $this->expectException(\ValueError::class);
        
        $this->service->getConnectionForType('Invalid Type');
    }
}