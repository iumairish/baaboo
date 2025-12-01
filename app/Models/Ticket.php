<?php

namespace App\Models;

use App\Enums\TicketStatus;
use App\Enums\TicketType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $description
 * @property TicketType $type
 * @property TicketStatus $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'description',
        'type',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => TicketType::class,
            'status' => TicketStatus::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Set the database connection for this model instance.
     */
    public function setConnection($name): static
    {
        $this->connection = $name;

        return $this;
    }

    /**
     * Get the ticket notes.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(TicketNote::class);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeStatus($query, TicketStatus $status)
    {
        return $query->where('status', $status->value);
    }

    /**
     * Scope a query to get recent tickets.
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
