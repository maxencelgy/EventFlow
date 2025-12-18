@extends('layouts.app')

@section('title', 'Modifier - ' . $event->title)

@section('content')
<div class="animate-in max-w-3xl">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('events.index') }}" class="hover:text-gray-900 transition-colors">Événements</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('events.show', $event) }}" class="hover:text-gray-900 transition-colors">{{ $event->title }}</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-900">Modifier</span>
    </nav>
    
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Modifier l'événement</h1>
    
    <form action="{{ route('events.update', $event) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Informations générales</h2>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" required
                        class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl resize-none transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">{{ old('description', $event->description) }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Date et lieu</h2>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" id="date" name="date" value="{{ old('date', $event->date->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Début</label>
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $event->start_time) }}" required
                            class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Fin</label>
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $event->end_time) }}" required
                            class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lieu</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}" required
                        class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl">
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Paramètres</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">Capacité</label>
                        <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="1" required
                            class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select id="status" name="status" required
                            class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl">
                            <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Publié</option>
                            <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-4 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-800 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] active:scale-[0.98]">
                Enregistrer
            </button>
            <a href="{{ route('events.show', $event) }}" class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
