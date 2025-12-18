@extends('layouts.app')

@section('title', 'Nouveau participant')

@section('content')
<div class="animate-in max-w-2xl">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('participants.index') }}" class="hover:text-gray-900">Participants</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-gray-900">Nouveau</span>
    </nav>
    
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Nouveau participant</h1>
    
    <form action="{{ route('participants.store') }}" method="POST">
        @csrf
        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Informations</h2>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                            class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
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
                        class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
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
                Créer
            </button>
            <a href="{{ route('participants.index') }}" class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
