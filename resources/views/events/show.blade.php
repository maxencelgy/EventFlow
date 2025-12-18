@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="animate-in">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('events.index') }}" class="hover:text-gray-900 transition-colors">Événements</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-900">{{ $event->title }}</span>
    </nav>
    
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full {{ $event->status === 'published' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $event->status === 'published' ? '● Publié' : ucfirst($event->status) }}
                </span>
                @if($event->date->isToday())
                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-blue-50 text-blue-700">
                        Aujourd'hui
                    </span>
                @endif
            </div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
            <p class="mt-2 text-gray-500">Organisé par {{ $event->user->name }}</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('events.registrations.create', $event) }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-800 transition-all duration-300 hover:shadow-lg hover:shadow-gray-900/25 hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Inscrire
            </a>
            @if($event->date->isToday() || $event->date->isPast())
                <a href="{{ route('checkin.index', $event) }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-emerald-600 rounded-full hover:bg-emerald-700 transition-all duration-300 hover:scale-[1.02]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Check-in
                </a>
            @endif
            <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-gray-700 bg-gray-100 rounded-full hover:bg-gray-200 transition-all">
                Modifier
            </a>
        </div>
    </div>
    
    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-gray-900">{{ $statistics['confirmed'] }}</div>
            <div class="mt-1 text-sm text-gray-500">Confirmés</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-amber-600">{{ $statistics['waiting'] }}</div>
            <div class="mt-1 text-sm text-gray-500">En attente</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-emerald-600">{{ $statistics['present'] }}</div>
            <div class="mt-1 text-sm text-gray-500">Présents</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-red-600">{{ $statistics['absent'] }}</div>
            <div class="mt-1 text-sm text-gray-500">Absents</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-gray-400">{{ $statistics['cancelled'] }}</div>
            <div class="mt-1 text-sm text-gray-500">Annulés</div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Informations</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date</p>
                        <p class="font-semibold text-gray-900">{{ $event->date->format('d F Y') }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Horaires</p>
                        <p class="font-semibold text-gray-900">{{ $event->start_time }} - {{ $event->end_time }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Lieu</p>
                        <p class="font-semibold text-gray-900">{{ $event->location }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Capacité</p>
                        <p class="font-semibold text-gray-900">{{ $event->max_participants }} places</p>
                    </div>
                </div>
            </div>
            
            @if($event->description)
            <div class="px-6 py-5 border-t border-gray-100">
                <p class="text-sm text-gray-600 leading-relaxed">{{ $event->description }}</p>
            </div>
            @endif
        </div>
        
        <!-- Registrations List -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Dernières inscriptions</h2>
                <a href="{{ route('events.registrations.index', $event) }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($event->registrations->take(8) as $registration)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-600 to-gray-800 text-white flex items-center justify-center text-sm font-semibold">
                                {{ strtoupper(substr($registration->participant->first_name, 0, 1) . substr($registration->participant->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $registration->participant->full_name }}</p>
                                <p class="text-sm text-gray-500">{{ $registration->participant->organization ?? 'Non spécifié' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full 
                            {{ $registration->status === 'inscrit' ? 'bg-blue-50 text-blue-700' : '' }}
                            {{ $registration->status === 'present' ? 'bg-emerald-50 text-emerald-700' : '' }}
                            {{ $registration->status === 'liste_attente' ? 'bg-amber-50 text-amber-700' : '' }}
                            {{ $registration->status === 'absent' ? 'bg-red-50 text-red-700' : '' }}
                            {{ $registration->status === 'annule' ? 'bg-gray-100 text-gray-600' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-gray-500">Aucune inscription pour le moment</p>
                        <a href="{{ route('events.registrations.create', $event) }}" class="mt-3 inline-block text-sm font-medium text-gray-900 hover:underline">
                            Ajouter un participant →
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Magic Link -->
    <div class="mt-8 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">Lien d'inscription public</h2>
                    <p class="text-sm text-gray-500">Partagez ce lien pour permettre aux participants de s'inscrire</p>
                </div>
            </div>
            @if($event->registration_open)
                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-emerald-100 text-emerald-700">
                    Ouvert
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-600">
                    Fermé
                </span>
            @endif
        </div>
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="flex-1 relative">
                    <input type="text" id="magic-link" value="{{ route('public.register', $event->registration_token) }}" readonly
                        class="w-full px-4 py-3.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-xl font-mono text-sm focus:outline-none">
                </div>
                <button onclick="copyLink()" class="inline-flex items-center gap-2 px-6 py-3.5 text-sm font-semibold text-white bg-gray-900 rounded-xl hover:bg-gray-800 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                    </svg>
                    Copier
                </button>
                <a href="{{ route('public.register', $event->registration_token) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Ouvrir
                </a>
            </div>
            <p id="copy-success" class="hidden mt-3 text-sm text-emerald-600">✓ Lien copié dans le presse-papier</p>
        </div>
    </div>
    
    <script>
    function copyLink() {
        const link = document.getElementById('magic-link');
        link.select();
        navigator.clipboard.writeText(link.value);
        document.getElementById('copy-success').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('copy-success').classList.add('hidden');
        }, 3000);
    }
    </script>
    
    <!-- Danger Zone -->
    <div class="mt-8 bg-white rounded-2xl border border-red-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-red-100 bg-red-50">
            <h2 class="font-semibold text-red-900">Zone de danger</h2>
        </div>
        <div class="p-6 flex items-center justify-between">
            <div>
                <p class="font-medium text-gray-900">Supprimer cet événement</p>
                <p class="mt-1 text-sm text-gray-500">Cette action est irréversible et supprimera toutes les inscriptions.</p>
            </div>
            <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-full hover:bg-red-700 transition-all">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
