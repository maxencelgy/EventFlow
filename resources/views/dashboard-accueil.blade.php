@extends('layouts.app')

@section('title', 'Accueil - Check-in')

@section('content')
<div class="animate-in">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Bonjour, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
        <p class="mt-1 text-gray-500">Interface de pointage - {{ now()->format('d F Y') }}</p>
    </div>
    
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-400 text-white hover-lift">
            <div class="relative z-10">
                <div class="text-4xl font-bold">{{ $statistics['today_events'] }}</div>
                <div class="mt-1 text-blue-100 font-medium">Événements aujourd'hui</div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-400 text-white hover-lift">
            <div class="relative z-10">
                <div class="text-4xl font-bold">{{ $statistics['today_checkins'] }}</div>
                <div class="mt-1 text-emerald-100 font-medium">Participants pointés</div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-400 text-white hover-lift">
            <div class="relative z-10">
                <div class="text-4xl font-bold">{{ $statistics['today_waiting'] }}</div>
                <div class="mt-1 text-amber-100 font-medium">En attente de pointage</div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Today's Events -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <span class="text-xl">🎯</span>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Événements du jour</h2>
            </div>
        </div>
        
        @if($todayEvents->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($todayEvents as $event)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                        <div class="flex flex-wrap items-center gap-4 mt-2">
                            <span class="flex items-center gap-1.5 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $event->start_time }} - {{ $event->end_time }}
                            </span>
                            <span class="flex items-center gap-1.5 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $event->location }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Stats mini -->
                        <div class="flex items-center gap-3 px-4 py-2 bg-gray-50 rounded-xl">
                            <div class="text-center">
                                <div class="text-lg font-bold text-emerald-600">{{ $event->present_count }}</div>
                                <div class="text-xs text-gray-500">Présents</div>
                            </div>
                            <div class="w-px h-8 bg-gray-200"></div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-amber-600">{{ $event->confirmed_count }}</div>
                                <div class="text-xs text-gray-500">En attente</div>
                            </div>
                        </div>
                        
                        <a href="{{ route('checkin.index', $event) }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-emerald-600 rounded-full hover:bg-emerald-700 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Accéder au check-in
                        </a>
                    </div>
                </div>
                
                <!-- Progress bar -->
                <div class="mt-4">
                    <div class="flex items-center justify-between text-sm mb-2">
                        <span class="text-gray-600">Progression du pointage</span>
                        <span class="font-medium text-gray-900">
                            {{ $event->present_count + $event->confirmed_count > 0 ? round(($event->present_count / ($event->present_count + $event->confirmed_count)) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-full transition-all duration-500" 
                            style="width: {{ $event->present_count + $event->confirmed_count > 0 ? round(($event->present_count / ($event->present_count + $event->confirmed_count)) * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-6 py-16 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun événement aujourd'hui</h3>
            <p class="text-gray-500 max-w-sm mx-auto">Il n'y a pas d'événement prévu pour aujourd'hui. Revenez demain ou contactez un administrateur.</p>
        </div>
        @endif
    </div>
</div>
@endsection
