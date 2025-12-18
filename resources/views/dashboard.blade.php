@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="animate-in">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Bonjour, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
            <p class="mt-1 text-gray-500">Voici un aperçu de vos événements</p>
        </div>
        <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-800 transition-all duration-300 hover:shadow-lg hover:shadow-gray-900/25 hover:scale-[1.02] active:scale-[0.98]">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nouvel événement
        </a>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-gray-900 to-gray-700 text-white hover-lift">
            <div class="relative z-10">
                <div class="text-4xl font-bold">{{ $statistics['total_events'] }}</div>
                <div class="mt-1 text-gray-300 font-medium">Événements</div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-400 text-white hover-lift">
            <div class="relative z-10">
                <div class="text-4xl font-bold">{{ $statistics['total_participants'] }}</div>
                <div class="mt-1 text-emerald-100 font-medium">Participants</div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-400 text-white hover-lift">
            <div class="relative z-10">
                <div class="text-4xl font-bold">{{ $statistics['total_registrations'] }}</div>
                <div class="mt-1 text-blue-100 font-medium">Inscriptions</div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
        
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-400 text-white hover-lift">
            <div class="relative z-10">
                <div class="text-4xl font-bold">{{ $statistics['today_checkins'] }}</div>
                <div class="mt-1 text-amber-100 font-medium">Pointages aujourd'hui</div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Événements du jour -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover-lift">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <span class="text-xl">🎯</span>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Événements du jour</h2>
                </div>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($todayEvents as $event)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $event->title }}</p>
                            <p class="text-sm text-gray-500">{{ $event->start_time }} - {{ $event->end_time }}</p>
                        </div>
                        <a href="{{ route('checkin.index', $event) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-full hover:bg-emerald-700 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Check-in
                        </a>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <span class="text-2xl">📅</span>
                        </div>
                        <p class="text-gray-500">Aucun événement prévu aujourd'hui</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Prochains événements -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover-lift">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <span class="text-xl">📆</span>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Prochains événements</h2>
                </div>
                <a href="{{ route('events.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($upcomingEvents as $event)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $event->title }}</p>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-sm text-gray-500">{{ $event->date->format('d/m/Y') }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                    {{ $event->confirmed_count }}/{{ $event->max_participants }}
                                </span>
                                @if($event->waiting_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-amber-50 text-amber-700">
                                        +{{ $event->waiting_count }} en attente
                                    </span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('events.show', $event) }}" class="ml-4 p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <span class="text-2xl">🗓️</span>
                        </div>
                        <p class="text-gray-500">Aucun événement à venir</p>
                        <a href="{{ route('events.create') }}" class="inline-block mt-4 text-sm font-medium text-gray-900 hover:underline">
                            Créer un événement →
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
