<?php

namespace App\Models;

use App\Enums\TicketStatus;
use App\Enums\TicketType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'description',
        'type',
        'status',
    ];

    /**
     * @return array<string, mixed>
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

    public function setConnection($name): static
    {
        $this->connection = $name;

        return $this;
    }

    /**
     * @return  HasMany<\App\Models\TicketNote>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(TicketNote::class);
    }

    /**
     * @param   Builder<Ticket> $query
     * @return  Builder<Ticket>
     */
    public function scopeStatus(Builder $query, TicketStatus $status): Builder
    {
        return $query->where('status', $status->value);
    }

    /**
     * @param   Builder<Ticket> $query
     * @return  Builder<Ticket>
     */
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
