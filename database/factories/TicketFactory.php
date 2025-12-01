<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(TicketType::cases());

        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'subject' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'type' => $type->value,
            'status' => fake()->randomElement(TicketStatus::cases())->value,
        ];
    }

    /**
     * Indicate that the ticket is open.
     */
    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TicketStatus::OPEN->value,
        ]);
    }

    /**
     * Indicate that the ticket is noted.
     */
    public function noted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TicketStatus::NOTED->value,
        ]);
    }

    /**
     * Set the ticket type.
     */
    public function ofType(TicketType $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type->value,
        ]);
    }
}