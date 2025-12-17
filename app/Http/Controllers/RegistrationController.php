<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegistrationController extends Controller
{
    public function __construct(
        protected RegistrationService $registrationService
    ) {}

    /**
     * Display registrations for an event.
     */
    public function index(Event $event): View
    {
        $registrations = $event->registrations()
            ->with('participant')
            ->orderByRaw("FIELD(status, 'present', 'inscrit', 'liste_attente', 'absent', 'annule')")
            ->orderBy('registered_at')
            ->get();

        $confirmed = $registrations->where('status', 'inscrit');
        $waitlist = $registrations->where('status', 'liste_attente')->sortBy('waiting_position');
        $present = $registrations->where('status', 'present');
        $cancelled = $registrations->where('status', 'annule');

        return view('registrations.index', compact('event', 'registrations', 'confirmed', 'waitlist', 'present', 'cancelled'));
    }

    /**
     * Show registration form.
     */
    public function create(Event $event): View
    {
        return view('registrations.create', compact('event'));
    }

    /**
     * Store a new registration.
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255',
            'function' => 'nullable|string|max:255',
        ]);

        try {
            $registration = $this->registrationService->register($event, $validated);

            $message = $registration->status === 'liste_attente'
                ? "Inscription en liste d'attente (position {$registration->waiting_position})."
                : 'Inscription confirmée avec succès.';

            return redirect()
                ->route('events.registrations.index', $event)
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a registration.
     */
    public function cancel(Event $event, Registration $registration): RedirectResponse
    {
        try {
            $this->registrationService->cancel($registration);

            return redirect()
                ->route('events.registrations.index', $event)
                ->with('success', 'Inscription annulée. Le premier en liste d\'attente a été promu si applicable.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Get event statistics.
     */
    public function statistics(Event $event): View
    {
        $statistics = $this->registrationService->getEventStatistics($event);

        return view('registrations.statistics', compact('event', 'statistics'));
    }
}
