<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PublicRegistrationController extends Controller
{
    public function __construct(
        protected RegistrationService $registrationService
    ) {}

    /**
     * Show the public registration form for an event.
     */
    public function show(string $token): View
    {
        $event = Event::where('registration_token', $token)
            ->where('status', 'published')
            ->firstOrFail();

        // Check if registration is open
        if (!$event->registration_open) {
            abort(403, 'Les inscriptions sont fermées pour cet événement.');
        }

        // Check if event date has passed
        if ($event->date->isPast()) {
            abort(403, 'Cet événement est déjà passé.');
        }

        return view('public.register', compact('event'));
    }

    /**
     * Process the public registration.
     */
    public function store(Request $request, string $token): RedirectResponse
    {
        $event = Event::where('registration_token', $token)
            ->where('status', 'published')
            ->firstOrFail();

        if (!$event->registration_open) {
            return back()->with('error', 'Les inscriptions sont fermées pour cet événement.');
        }

        if ($event->date->isPast()) {
            return back()->with('error', 'Cet événement est déjà passé.');
        }

        // Validate input
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255',
            'function' => 'nullable|string|max:255',
        ]);

        // Prepare participant data for RegistrationService
        $participantData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'organization' => $validated['organization'] ?? null,
            'function' => $validated['function'] ?? null,
        ];

        // Register participant using the service
        try {
            $registration = $this->registrationService->register($event, $participantData);
            
            return redirect()->route('public.confirmation', [
                'token' => $token,
                'status' => $registration->status
            ])->with('success', 'Inscription réussie !');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show confirmation page.
     */
    public function confirmation(string $token, Request $request): View
    {
        $event = Event::where('registration_token', $token)->firstOrFail();
        $status = $request->query('status', 'inscrit');

        return view('public.confirmation', compact('event', 'status'));
    }
}
