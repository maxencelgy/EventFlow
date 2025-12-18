@extends('layouts.app')

@section('title', 'Nouvelle inscription - ' . $event->title)

@section('content')
<div class="animate-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form -->
        <div class="lg:col-span-2">
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('events.show', $event) }}" class="hover:text-gray-900">{{ $event->title }}</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                <span class="text-gray-900">Nouvelle inscription</span>
            </nav>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Inscrire un participant</h1>
            
            <form action="{{ route('events.registrations.store', $event) }}" method="POST">
                @csrf
                
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Informations participant</h2>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                    class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
                                @error('first_name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                    class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                                placeholder="participant@example.com">
                            @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone <span class="text-gray-400">(optionnel)</span></label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label for="organization" class="block text-sm font-medium text-gray-700 mb-2">Organisation <span class="text-gray-400">(optionnel)</span></label>
                                <input type="text" id="organization" name="organization" value="{{ old('organization') }}"
                                    class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
                            </div>
                            <div>
                                <label for="function" class="block text-sm font-medium text-gray-700 mb-2">Fonction <span class="text-gray-400">(optionnel)</span></label>
                                <input type="text" id="function" name="function" value="{{ old('function') }}"
                                    class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center gap-2 px-8 py-4 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-800 transition-all hover:scale-[1.02] active:scale-[0.98]">
                        Inscrire
                    </button>
                    <a href="{{ route('events.registrations.index', $event) }}" class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden sticky top-8">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Récapitulatif</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Événement</p>
                        <p class="font-semibold text-gray-900">{{ $event->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date</p>
                        <p class="font-medium text-gray-900">{{ $event->date->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Horaires</p>
                        <p class="font-medium text-gray-900">{{ $event->start_time }} - {{ $event->end_time }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Lieu</p>
                        <p class="font-medium text-gray-900">{{ $event->location }}</p>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-500">Places disponibles</p>
                        @if($event->isFull())
                            <p class="font-semibold text-amber-600">Complet - Liste d'attente</p>
                        @else
                            <p class="font-semibold text-emerald-600">{{ $event->available_spots }} / {{ $event->max_participants }}</p>
                        @endif
                    </div>
                </div>
                
                @if($event->isFull())
                <div class="px-6 py-4 bg-amber-50 border-t border-amber-100">
                    <p class="text-sm text-amber-800">
                        <strong>Note :</strong> Le participant sera ajouté à la liste d'attente.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
