<?php

namespace App\Enums;

enum TicketType: string
{
    case TECHNICAL_ISSUES = 'Technical Issues';
    case ACCOUNT_BILLING = 'Account & Billing';
    case PRODUCT_SERVICE = 'Product & Service';
    case GENERAL_INQUIRY = 'General Inquiry';
    case FEEDBACK_SUGGESTIONS = 'Feedback & Suggestions';

    /**
     * Get the database connection name for this ticket type.
     */
    public function getDatabaseConnection(): string
    {
        return match ($this) {
            self::TECHNICAL_ISSUES => 'technical_department',
            self::ACCOUNT_BILLING => 'account_department',
            self::PRODUCT_SERVICE => 'product_department',
            self::GENERAL_INQUIRY => 'general_department',
            self::FEEDBACK_SUGGESTIONS => 'feedback_department',
        };
    }

    /**
     * Get all ticket types as an associative array for dropdowns.
     *
     * @return array<string, string>
     */
    public static function toArray(): array
    {
        $types = [];
        foreach (self::cases() as $case) {
            $types[$case->value] = $case->value;
        }
        return $types;
    }

    /**
     * Get display label for the ticket type.
     */
    public function label(): string
    {
        return $this->value;
    }
}