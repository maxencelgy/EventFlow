<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'event_session_id',
        'status',
    ];

    /**
     * Get the main event registration.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the event session.
     */
    public function eventSession(): BelongsTo
    {
        return $this->belongsTo(EventSession::class);
    }

    /**
     * Mark as present.
     */
    public function markPresent(): void
    {
        $this->update(['status' => 'present']);
    }

    /**
     * Mark as absent.
     */
    public function markAbsent(): void
    {
        $this->update(['status' => 'absent']);
    }
}
