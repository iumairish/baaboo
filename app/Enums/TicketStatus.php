<?php

namespace App\Enums;

enum TicketStatus: string
{
    case OPEN = 'open';
    case NOTED = 'noted';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    /**
     * Get the display label for this status.
     */
    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::NOTED => 'Noted',
            self::IN_PROGRESS => 'In Progress',
            self::RESOLVED => 'Resolved',
            self::CLOSED => 'Closed',
        };
    }

    /**
     * Get the badge color class for this status.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::OPEN => 'badge-primary',
            self::NOTED => 'badge-info',
            self::IN_PROGRESS => 'badge-warning',
            self::RESOLVED => 'badge-success',
            self::CLOSED => 'badge-secondary',
        };
    }

    /**
     * Get all statuses as an associative array.
     *
     * @return array<string, string>
     */
    public static function toArray(): array
    {
        $statuses = [];
        foreach (self::cases() as $case) {
            $statuses[$case->value] = $case->label();
        }
        return $statuses;
    }
}