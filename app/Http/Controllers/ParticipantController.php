<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ParticipantController extends Controller
{
    /**
     * Display a listing of participants.
     */
    public function index(Request $request): View
    {
        $query = Participant::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('organization', 'like', "%{$search}%");
            });
        }

        $participants = $query->withCount('registrations')
            ->orderBy('last_name')
            ->paginate(20);

        return view('participants.index', compact('participants'));
    }

    /**
     * Show the form for creating a new participant.
     */
    public function create(): View
    {
        return view('participants.create');
    }

    /**
     * Store a newly created participant.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:participants,email',
            'phone' => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255',
            'function' => 'nullable|string|max:255',
        ]);

        $participant = Participant::create($validated);

        return redirect()
            ->route('participants.show', $participant)
            ->with('success', 'Participant créé avec succès.');
    }

    /**
     * Display the specified participant.
     */
    public function show(Participant $participant): View
    {
        $participant->load(['registrations.event']);

        return view('participants.show', compact('participant'));
    }

    /**
     * Show the form for editing the specified participant.
     */
    public function edit(Participant $participant): View
    {
        return view('participants.edit', compact('participant'));
    }

    /**
     * Update the specified participant.
     */
    public function update(Request $request, Participant $participant): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:participants,email,' . $participant->id,
            'phone' => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255',
            'function' => 'nullable|string|max:255',
        ]);

        $participant->update($validated);

        return redirect()
            ->route('participants.show', $participant)
            ->with('success', 'Participant mis à jour avec succès.');
    }

    /**
     * Remove the specified participant.
     */
    public function destroy(Participant $participant): RedirectResponse
    {
        $participant->delete();

        return redirect()
            ->route('participants.index')
            ->with('success', 'Participant supprimé avec succès.');
    }
}
