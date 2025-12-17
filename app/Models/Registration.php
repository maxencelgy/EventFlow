<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'participant_id',
        'status',
        'registered_at',
        'checked_in_at',
        'waiting_position',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'checked_in_at' => 'datetime',
        ];
    }

    /**
     * Get the event.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the participant.
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Get session registrations.
     */
    public function sessionRegistrations(): HasMany
    {
        return $this->hasMany(SessionRegistration::class);
    }

    /**
     * Check in the participant.
     */
    public function checkIn(): void
    {
        $this->update([
            'status' => 'present',
            'checked_in_at' => now(),
        ]);
    }

    /**
     * Mark as absent.
     */
    public function markAbsent(): void
    {
        $this->update(['status' => 'absent']);
    }

    /**
     * Cancel registration.
     */
    public function cancel(): void
    {
        $previousStatus = $this->status;
        $this->update([
            'status' => 'annule',
            'waiting_position' => null,
        ]);

        // If was confirmed, promote first from waitlist
        if ($previousStatus === 'inscrit') {
            $this->promoteFirstFromWaitlist();
        }
    }

    /**
     * Promote first person from waitlist for this event.
     */
    protected function promoteFirstFromWaitlist(): void
    {
        $first = static::where('event_id', $this->event_id)
            ->where('status', 'liste_attente')
            ->orderBy('waiting_position')
            ->first();

        if ($first) {
            $oldPosition = $first->waiting_position;
            
            $first->update([
                'status' => 'inscrit',
                'waiting_position' => null,
            ]);

            // Reorder remaining waitlist
            if ($oldPosition) {
                static::where('event_id', $this->event_id)
                    ->where('status', 'liste_attente')
                    ->where('waiting_position', '>', $oldPosition)
                    ->decrement('waiting_position');
            }
        }
    }

    /**
     * Scope for confirmed registrations.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'inscrit');
    }

    /**
     * Scope for waitlist registrations.
     */
    public function scopeWaitlist($query)
    {
        return $query->where('status', 'liste_attente')->orderBy('waiting_position');
    }

    /**
     * Scope for present registrations.
     */
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }
}
