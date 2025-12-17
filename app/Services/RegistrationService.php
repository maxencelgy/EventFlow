<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    /**
     * Register a participant to an event.
     * Handles capacity limits and waiting list automatically.
     *
     * @param Event $event
     * @param array $participantData
     * @return Registration
     * @throws \Exception
     */
    public function register(Event $event, array $participantData): Registration
    {
        return DB::transaction(function () use ($event, $participantData) {
            // Find or create participant
            $participant = Participant::findOrCreateByEmail($participantData);

            // Check if already registered
            $existingRegistration = Registration::where('event_id', $event->id)
                ->where('participant_id', $participant->id)
                ->first();

            if ($existingRegistration) {
                if ($existingRegistration->status === 'annule') {
                    // Re-register if previously cancelled
                    return $this->reRegister($existingRegistration, $event);
                }
                throw new \Exception('Ce participant est déjà inscrit à cet événement.');
            }

            // Determine status based on capacity
            $status = 'inscrit';
            $waitingPosition = null;

            if ($event->isFull()) {
                $status = 'liste_attente';
                $waitingPosition = $this->getNextWaitingPosition($event);
            }

            return Registration::create([
                'event_id' => $event->id,
                'participant_id' => $participant->id,
                'status' => $status,
                'registered_at' => now(),
                'waiting_position' => $waitingPosition,
            ]);
        });
    }

    /**
     * Re-register a previously cancelled registration.
     */
    protected function reRegister(Registration $registration, Event $event): Registration
    {
        if ($event->isFull()) {
            $registration->update([
                'status' => 'liste_attente',
                'waiting_position' => $this->getNextWaitingPosition($event),
                'registered_at' => now(),
            ]);
        } else {
            $registration->update([
                'status' => 'inscrit',
                'waiting_position' => null,
                'registered_at' => now(),
            ]);
        }

        return $registration->fresh();
    }

    /**
     * Get next waiting position for an event.
     */
    protected function getNextWaitingPosition(Event $event): int
    {
        $maxPosition = Registration::where('event_id', $event->id)
            ->where('status', 'liste_attente')
            ->max('waiting_position');

        return ($maxPosition ?? 0) + 1;
    }

    /**
     * Cancel a registration and promote from waitlist if applicable.
     */
    public function cancel(Registration $registration): void
    {
        $registration->cancel();
    }

    /**
     * Check in a participant.
     */
    public function checkIn(Registration $registration): void
    {
        if (!in_array($registration->status, ['inscrit', 'liste_attente'])) {
            throw new \Exception('Ce participant ne peut pas être pointé.');
        }

        $registration->checkIn();
    }

    /**
     * Mark participant as absent.
     */
    public function markAbsent(Registration $registration): void
    {
        $registration->markAbsent();
    }

    /**
     * Get event statistics.
     */
    public function getEventStatistics(Event $event): array
    {
        $registrations = $event->registrations;

        $byStatus = $registrations->groupBy('status')->map->count();
        $byOrganization = $registrations->load('participant')
            ->pluck('participant.organization')
            ->filter()
            ->countBy()
            ->sortDesc();

        return [
            'total_registrations' => $registrations->count(),
            'confirmed' => $byStatus['inscrit'] ?? 0,
            'waiting_list' => $byStatus['liste_attente'] ?? 0,
            'cancelled' => $byStatus['annule'] ?? 0,
            'present' => $byStatus['present'] ?? 0,
            'absent' => $byStatus['absent'] ?? 0,
            'available_spots' => $event->available_spots,
            'capacity' => $event->max_participants,
            'fill_rate' => $event->max_participants > 0 
                ? round((($byStatus['inscrit'] ?? 0) + ($byStatus['present'] ?? 0)) / $event->max_participants * 100, 2) 
                : 0,
            'attendance_rate' => $event->attendance_rate,
            'by_organization' => $byOrganization->take(10)->toArray(),
        ];
    }
}
