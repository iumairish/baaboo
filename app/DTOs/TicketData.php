<?php

namespace App\DTOs;

use App\Enums\TicketStatus;
use App\Enums\TicketType;

/**
 * Data Transfer Object for Ticket data.
 * Ensures type safety and validation across layers.
 */
readonly class TicketData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $subject,
        public string $description,
        public TicketType $type,
        public TicketStatus $status = TicketStatus::OPEN,
        public ?int $id = null,
    ) {}

    /**
     * Create DTO from request data.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            subject: $data['subject'],
            description: $data['description'],
            type: TicketType::from($data['type']),
            status: isset($data['status'])
                ? TicketStatus::from($data['status'])
                : TicketStatus::OPEN,
            id: $data['id'] ?? null,
        );
    }

    /**
     * Convert DTO to array for database operations.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'description' => $this->description,
            'type' => $this->type->value,
            'status' => $this->status->value,
        ];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }

        return $data;
    }

    /**
     * Get the database connection for this ticket.
     */
    public function getDatabaseConnection(): string
    {
        return $this->type->getDatabaseConnection();
    }
}
