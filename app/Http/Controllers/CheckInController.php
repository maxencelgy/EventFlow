<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CheckInController extends Controller
{
    public function __construct(
        protected RegistrationService $registrationService
    ) {}

    /**
     * Display check-in interface for an event.
     */
    public function index(Event $event): View
    {
        $registrations = $event->registrations()
            ->with('participant')
            ->whereIn('status', ['inscrit', 'present', 'absent'])
            ->orderBy('participant_id')
            ->get();

        $statistics = [
            'total' => $registrations->count(),
            'present' => $registrations->where('status', 'present')->count(),
            'waiting' => $registrations->where('status', 'inscrit')->count(),
            'absent' => $registrations->where('status', 'absent')->count(),
        ];

        return view('checkin.index', compact('event', 'registrations', 'statistics'));
    }

    /**
     * Check in a participant.
     */
    public function checkIn(Event $event, Registration $registration): RedirectResponse
    {
        try {
            $this->registrationService->checkIn($registration);

            return redirect()
                ->route('checkin.index', $event)
                ->with('success', "{$registration->participant->full_name} pointé(e) avec succès.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Mark participant as absent.
     */
    public function markAbsent(Event $event, Registration $registration): RedirectResponse
    {
        try {
            $this->registrationService->markAbsent($registration);

            return redirect()
                ->route('checkin.index', $event)
                ->with('success', "{$registration->participant->full_name} marqué(e) absent(e).");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Search for a participant by name or email.
     */
    public function search(Request $request, Event $event): View
    {
        $query = $request->input('q', '');

        $registrations = $event->registrations()
            ->with('participant')
            ->whereIn('status', ['inscrit', 'present', 'absent'])
            ->whereHas('participant', function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->get();

        $statistics = [
            'total' => $event->registrations()->whereIn('status', ['inscrit', 'present', 'absent'])->count(),
            'present' => $event->registrations()->where('status', 'present')->count(),
            'waiting' => $event->registrations()->where('status', 'inscrit')->count(),
            'absent' => $event->registrations()->where('status', 'absent')->count(),
        ];

        return view('checkin.index', compact('event', 'registrations', 'statistics', 'query'));
    }
}
