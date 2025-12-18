@extends('layouts.app')

@section('title', 'Inscriptions - ' . $event->title)

@section('content')
<div class="animate-in">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('events.index') }}" class="hover:text-gray-900">Événements</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                <a href="{{ route('events.show', $event) }}" class="hover:text-gray-900">{{ $event->title }}</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                <span class="text-gray-900">Inscriptions</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Inscriptions</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('events.registrations.create', $event) }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-800 transition-all hover:scale-[1.02]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Inscrire
            </a>
            <a href="{{ route('events.statistics', $event) }}" class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-full hover:bg-gray-200 transition-all">
                Statistiques
            </a>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-gray-900">{{ $confirmed->count() }}</div>
            <div class="mt-1 text-sm text-gray-500">Confirmés</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-amber-600">{{ $waitlist->count() }}</div>
            <div class="mt-1 text-sm text-gray-500">Liste d'attente</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-emerald-600">{{ $present->count() }}</div>
            <div class="mt-1 text-sm text-gray-500">Présents</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $event->max_participants - $confirmed->count() - $present->count() }}</div>
            <div class="mt-1 text-sm text-gray-500">Places restantes</div>
        </div>
    </div>
    
    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($registrations->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Participant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Organisation</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Inscription</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($registrations as $registration)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-600 to-gray-800 text-white flex items-center justify-center text-sm font-semibold">
                                    {{ strtoupper(substr($registration->participant->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $registration->participant->full_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $registration->participant->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $registration->participant->organization ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full 
                                {{ $registration->status === 'inscrit' ? 'bg-blue-50 text-blue-700' : '' }}
                                {{ $registration->status === 'present' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                {{ $registration->status === 'liste_attente' ? 'bg-amber-50 text-amber-700' : '' }}
                                {{ $registration->status === 'absent' ? 'bg-red-50 text-red-700' : '' }}
                                {{ $registration->status === 'annule' ? 'bg-gray-100 text-gray-600' : '' }}">
                                @if($registration->status === 'liste_attente')
                                    #{{ $registration->waiting_position }} -
                                @endif
                                {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $registration->registered_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            @if(!in_array($registration->status, ['annule', 'present', 'absent']))
                                <form action="{{ route('events.registrations.cancel', [$event, $registration]) }}" method="POST" onsubmit="return confirm('Annuler cette inscription ?');">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                        Annuler
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <p class="text-gray-500">Aucune inscription pour cet événement</p>
            <a href="{{ route('events.registrations.create', $event) }}" class="mt-4 inline-block text-sm font-medium text-gray-900 hover:underline">
                Ajouter un participant →
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
