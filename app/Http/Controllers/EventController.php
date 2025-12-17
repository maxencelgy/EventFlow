<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(): View
    {
        $events = Event::with('user')
            ->withCount(['registrations as confirmed_count' => function ($query) {
                $query->where('status', 'inscrit');
            }])
            ->withCount(['registrations as waiting_count' => function ($query) {
                $query->where('status', 'liste_attente');
            }])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(): View
    {
        return view('events.create');
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'status' => 'required|in:draft,published',
        ]);

        $validated['user_id'] = auth()->id();

        $event = Event::create($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Événement créé avec succès.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event): View
    {
        $event->load(['user', 'sessions.room', 'registrations.participant']);

        $statistics = [
            'confirmed' => $event->registrations->where('status', 'inscrit')->count(),
            'waiting' => $event->registrations->where('status', 'liste_attente')->count(),
            'present' => $event->registrations->where('status', 'present')->count(),
            'absent' => $event->registrations->where('status', 'absent')->count(),
            'cancelled' => $event->registrations->where('status', 'annule')->count(),
        ];

        return view('events.show', compact('event', 'statistics'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event): View
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,cancelled,completed',
        ]);

        $event->update($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Événement mis à jour avec succès.');
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Événement supprimé avec succès.');
    }
}
