<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'room_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'max_participants',
    ];

    /**
     * Get the event this session belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the room for this session.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get session registrations.
     */
    public function sessionRegistrations(): HasMany
    {
        return $this->hasMany(SessionRegistration::class);
    }

    /**
     * Get max capacity (uses room capacity if session max not set).
     */
    public function getEffectiveMaxParticipantsAttribute(): int
    {
        if ($this->max_participants) {
            return $this->max_participants;
        }

        return $this->room?->capacity ?? 0;
    }

    /**
     * Get current registration count.
     */
    public function getRegistrationCountAttribute(): int
    {
        return $this->sessionRegistrations()->whereIn('status', ['inscrit', 'present'])->count();
    }

    /**
     * Check if session is full.
     */
    public function isFull(): bool
    {
        $max = $this->effective_max_participants;
        return $max > 0 && $this->registration_count >= $max;
    }
}
