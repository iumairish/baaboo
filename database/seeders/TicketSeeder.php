<?php

namespace Database\Seeders;

use App\Enums\TicketType;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (TicketType::cases() as $type) {
            $connection = $type->getDatabaseConnection();

            $this->command->info("Creating tickets in {$connection}...");

            // Create 10 tickets of each type in the CORRECT database
            for ($i = 0; $i < 10; $i++) {
                $ticket = Ticket::factory()
                    ->ofType($type)
                    ->make([
                        'type' => $type->value,
                    ]);

                // CRITICAL: Set connection BEFORE saving
                $ticket->setConnection($connection);
                $ticket->save();
            }

            $this->command->info("✓ Created 10 tickets in {$connection}");
        }

        $this->command->info('');
        $this->command->info('Total: 50 sample tickets created across all departments');
    }
}
