<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - <?php echo e($event->title); ?></title>
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
    </style>
</head>
<body class="min-h-full gradient-mesh py-12 px-4">
    <div class="max-w-2xl mx-auto animate-in">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur mb-4">
                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white"><?php echo e($event->title); ?></h1>
            <p class="mt-2 text-white/70"><?php echo e($event->date->format('d F Y')); ?> • <?php echo e($event->start_time); ?> - <?php echo e($event->end_time); ?></p>
            <p class="mt-1 text-white/50"><?php echo e($event->location); ?></p>
        </div>
        
        <!-- Card -->
        <div class="glass rounded-3xl shadow-2xl overflow-hidden">
            <!-- Event Info -->
            <div class="px-8 py-6 bg-gray-50 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Places disponibles</p>
                        <?php if($event->isFull()): ?>
                            <p class="text-lg font-semibold text-amber-600">Complet - Liste d'attente</p>
                        <?php else: ?>
                            <p class="text-lg font-semibold text-emerald-600"><?php echo e($event->available_spots); ?> / <?php echo e($event->max_participants); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if($event->isFull()): ?>
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-amber-100 text-amber-700">
                            Liste d'attente
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-emerald-100 text-emerald-700">
                            Inscriptions ouvertes
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Form -->
            <div class="p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Formulaire d'inscription</h2>
                
                <?php if(session('error')): ?>
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100">
                        <p class="text-sm text-red-600"><?php echo e(session('error')); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if($errors->any()): ?>
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100">
                        <ul class="text-sm text-red-600 space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo e(route('public.register.store', $event->registration_token)); ?>" method="POST" class="space-y-5">
                    <?php echo csrf_field(); ?>
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo e(old('first_name')); ?>" required
                                class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                                placeholder="Jean">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo e(old('last_name')); ?>" required
                                class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                                placeholder="Dupont">
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email *</label>
                        <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required
                            class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                            placeholder="jean.dupont@exemple.com">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone <span class="text-gray-400">(optionnel)</span></label>
                        <input type="tel" id="phone" name="phone" value="<?php echo e(old('phone')); ?>"
                            class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                            placeholder="06 12 34 56 78">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label for="organization" class="block text-sm font-medium text-gray-700 mb-2">Organisation <span class="text-gray-400">(optionnel)</span></label>
                            <input type="text" id="organization" name="organization" value="<?php echo e(old('organization')); ?>"
                                class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                                placeholder="Nom de votre entreprise">
                        </div>
                        <div>
                            <label for="function" class="block text-sm font-medium text-gray-700 mb-2">Fonction <span class="text-gray-400">(optionnel)</span></label>
                            <input type="text" id="function" name="function" value="<?php echo e(old('function')); ?>"
                                class="w-full px-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                                placeholder="Votre poste">
                        </div>
                    </div>
                    
                    <?php if($event->isFull()): ?>
                        <div class="p-4 rounded-xl bg-amber-50 border border-amber-100">
                            <p class="text-sm text-amber-800">
                                <strong>Note :</strong> L'événement est complet. Vous serez ajouté à la liste d'attente et notifié si une place se libère.
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="w-full py-4 text-sm font-semibold text-white bg-gray-900 rounded-xl transition-all duration-300 hover:bg-gray-800 hover:shadow-lg hover:shadow-gray-900/25 active:scale-[0.98]">
                        <?php if($event->isFull()): ?>
                            S'inscrire sur la liste d'attente
                        <?php else: ?>
                            S'inscrire à l'événement
                        <?php endif; ?>
                    </button>
                </form>
            </div>
            
            <?php if($event->description): ?>
            <div class="px-8 py-6 border-t border-gray-100 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">À propos de cet événement</h3>
                <p class="text-sm text-gray-600 leading-relaxed"><?php echo e($event->description); ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Footer -->
        <p class="mt-8 text-center text-sm text-white/40">
            Propulsé par EventFlow
        </p>
    </div>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/public/register.blade.php ENDPATH**/ ?>