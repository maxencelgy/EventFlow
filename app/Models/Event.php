<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'location',
        'max_participants',
        'status',
        'registration_token',
        'registration_open',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->registration_token)) {
                $event->registration_token = \Illuminate\Support\Str::random(32);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    /**
     * Get the organizer of this event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for user relationship.
     */
    public function organizer(): BelongsTo
    {
        return $this->user();
    }

    /**
     * Get sessions/ateliers for this event.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(EventSession::class);
    }

    /**
     * Get registrations for this event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get confirmed registrations (not cancelled or on waitlist).
     */
    public function confirmedRegistrations(): HasMany
    {
        return $this->registrations()->where('status', 'inscrit');
    }

    /**
     * Get participants through registrations.
     */
    public function participants(): HasManyThrough
    {
        return $this->hasManyThrough(Participant::class, Registration::class, 'event_id', 'id', 'id', 'participant_id');
    }

    /**
     * Get current participant count.
     */
    public function getParticipantCountAttribute(): int
    {
        return $this->registrations()->whereIn('status', ['inscrit', 'present'])->count();
    }

    /**
     * Get waiting list count.
     */
    public function getWaitingCountAttribute(): int
    {
        return $this->registrations()->where('status', 'liste_attente')->count();
    }

    /**
     * Check if event is full.
     */
    public function isFull(): bool
    {
        return $this->participant_count >= $this->max_participants;
    }

    /**
     * Get available spots.
     */
    public function getAvailableSpotsAttribute(): int
    {
        return max(0, $this->max_participants - $this->participant_count);
    }

    /**
     * Get attendance rate (present / inscrit).
     */
    public function getAttendanceRateAttribute(): float
    {
        $inscrit = $this->registrations()->where('status', 'inscrit')->count();
        $present = $this->registrations()->where('status', 'present')->count();
        
        if ($inscrit + $present === 0) {
            return 0;
        }

        return round(($present / ($inscrit + $present)) * 100, 2);
    }
}
