<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - EventFlow</title>
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
        
        .input-focus:focus {
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="h-full gradient-mesh">
    <div class="min-h-full flex items-center justify-center p-6">
        <div class="animate-in w-full max-w-md">
            <!-- Card -->
            <div class="glass rounded-3xl shadow-2xl p-10">
                <!-- Logo -->
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-900 text-white mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Bienvenue</h1>
                    <p class="mt-2 text-gray-500">Connectez-vous à EventFlow</p>
                </div>
                
                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100">
                        <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="input-focus w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all duration-200 focus:outline-none focus:border-gray-400 hover:border-gray-300"
                            placeholder="vous@exemple.com">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <input type="password" id="password" name="password" required
                            class="input-focus w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all duration-200 focus:outline-none focus:border-gray-400 hover:border-gray-300"
                            placeholder="••••••••">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-500">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Se souvenir de moi</label>
                    </div>
                    
                    <button type="submit" class="w-full py-4 text-sm font-semibold text-white bg-gray-900 rounded-xl transition-all duration-300 hover:bg-gray-800 hover:shadow-lg hover:shadow-gray-900/25 active:scale-[0.98]">
                        Se connecter
                    </button>
                </form>
                
                <p class="mt-8 text-center text-sm text-gray-500">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" class="font-semibold text-gray-900 hover:underline">S'inscrire</a>
                </p>
            </div>
            
            <!-- Footer -->
            <p class="mt-8 text-center text-sm text-white/50">
                EventFlow © {{ date('Y') }}
            </p>
        </div>
    </div>
</body>
</html>
