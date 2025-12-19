<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription confirmée - {{ $event->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { -webkit-font-smoothing: antialiased; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        
        .gradient-mesh {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }
        
        .animate-in {
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        @keyframes slideUp {
            0% { opacity: 0; transform: translateY(30px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        
        .checkmark {
            animation: checkmark 0.5s ease-in-out 0.3s forwards;
            opacity: 0;
            transform: scale(0);
        }
        
        @keyframes checkmark {
            0% { opacity: 0; transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body class="min-h-full gradient-mesh flex items-center justify-center py-12 px-4">
    <div class="max-w-lg w-full animate-in">
        <div class="glass rounded-3xl shadow-2xl overflow-hidden text-center p-10">
            <!-- Icon -->
            @if($status === 'inscrit')
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 mb-6 checkmark">
                    <svg class="w-10 h-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Inscription confirmée !</h1>
                <p class="text-gray-600 mb-6">Vous êtes maintenant inscrit à cet événement.</p>
            @else
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-amber-100 mb-6 checkmark">
                    <svg class="w-10 h-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Liste d'attente</h1>
                <p class="text-gray-600 mb-6">Vous avez été ajouté à la liste d'attente. Nous vous contacterons si une place se libère.</p>
            @endif
            
            <!-- Event Details -->
            <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                <h2 class="font-semibold text-gray-900 mb-4">{{ $event->title }}</h2>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $event->date->format('d F Y') }}</span>
                    </div>
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $event->start_time }} - {{ $event->end_time }}</span>
                    </div>
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        <span>{{ $event->location }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Next Steps -->
            <div class="text-left bg-blue-50 rounded-2xl p-5 mb-6">
                <h3 class="font-semibold text-blue-900 mb-2">Prochaines étapes</h3>
                <ul class="text-sm text-blue-800 space-y-2">
                    @if($status === 'inscrit')
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500">•</span>
                            <span>Notez la date dans votre agenda</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500">•</span>
                            <span>Présentez-vous à l'accueil le jour de l'événement</span>
                        </li>
                    @else
                        <li class="flex items-start gap-2">
                            <span class="text-amber-500">•</span>
                            <span>Gardez cette page dans vos favoris</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-amber-500">•</span>
                            <span>Nous vous contacterons si une place se libère</span>
                        </li>
                    @endif
                </ul>
            </div>
            
            <a href="{{ route('public.register', $event->registration_token) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour au formulaire
            </a>
        </div>
        
        <p class="mt-8 text-center text-sm text-white/40">
            Propulsé par EventFlow
        </p>
    </div>
</body>
</html>
