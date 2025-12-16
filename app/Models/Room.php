<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'location',
        'description',
    ];

    /**
     * Get sessions held in this room.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(EventSession::class);
    }

    /**
     * Check if room has available capacity for a session.
     */
    public function hasCapacityFor(int $count): bool
    {
        return $this->capacity >= $count;
    }
}
