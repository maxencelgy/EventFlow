<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Registration;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // For "accueil" role: show only check-in focused dashboard
        if ($user->isAccueil()) {
            $todayEvents = Event::whereDate('date', now()->toDateString())
                ->where('status', 'published')
                ->withCount(['registrations as confirmed_count' => fn($q) => $q->where('status', 'inscrit')])
                ->withCount(['registrations as present_count' => fn($q) => $q->where('status', 'present')])
                ->withCount(['registrations as absent_count' => fn($q) => $q->where('status', 'absent')])
                ->get();

            $statistics = [
                'today_events' => $todayEvents->count(),
                'today_checkins' => Registration::whereHas('event', fn($q) => $q->whereDate('date', now()))
                    ->where('status', 'present')
                    ->count(),
                'today_waiting' => Registration::whereHas('event', fn($q) => $q->whereDate('date', now()))
                    ->where('status', 'inscrit')
                    ->count(),
            ];

            return view('dashboard-accueil', compact('todayEvents', 'statistics'));
        }

        // For admin and chef_projet: full dashboard
        $upcomingEvents = Event::where('date', '>=', now()->toDateString())
            ->where('status', 'published')
            ->orderBy('date')
            ->limit(5)
            ->withCount(['registrations as confirmed_count' => fn($q) => $q->where('status', 'inscrit')])
            ->withCount(['registrations as waiting_count' => fn($q) => $q->where('status', 'liste_attente')])
            ->get();

        $todayEvents = Event::whereDate('date', now()->toDateString())
            ->where('status', 'published')
            ->withCount(['registrations as confirmed_count' => fn($q) => $q->where('status', 'inscrit')])
            ->withCount(['registrations as present_count' => fn($q) => $q->where('status', 'present')])
            ->get();

        $statistics = [
            'total_events' => Event::count(),
            'total_participants' => Participant::count(),
            'total_registrations' => Registration::count(),
            'upcoming_events' => Event::where('date', '>=', now()->toDateString())->count(),
            'today_checkins' => Registration::whereHas('event', fn($q) => $q->whereDate('date', now()))
                ->where('status', 'present')
                ->count(),
        ];

        return view('dashboard', compact('upcomingEvents', 'todayEvents', 'statistics'));
    }
}
