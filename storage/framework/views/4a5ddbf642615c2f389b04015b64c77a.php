<?php $__env->startSection('title', $participant->full_name); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-in">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?php echo e(route('participants.index')); ?>" class="hover:text-gray-900">Participants</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-gray-900"><?php echo e($participant->full_name); ?></span>
    </nav>
    
    <div class="flex items-start justify-between mb-8">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 text-white flex items-center justify-center text-xl font-bold">
                <?php echo e(strtoupper(substr($participant->first_name, 0, 1) . substr($participant->last_name, 0, 1))); ?>

            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900"><?php echo e($participant->full_name); ?></h1>
                <p class="text-gray-500"><?php echo e($participant->email); ?></p>
            </div>
        </div>
        <a href="<?php echo e(route('participants.edit', $participant)); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-full hover:bg-gray-200 transition-all">
            Modifier
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Informations</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Nom complet</p>
                    <p class="font-medium text-gray-900"><?php echo e($participant->first_name); ?> <?php echo e($participant->last_name); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-900"><?php echo e($participant->email); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Téléphone</p>
                    <p class="font-medium text-gray-900"><?php echo e($participant->phone ?? '—'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Organisation</p>
                    <p class="font-medium text-gray-900"><?php echo e($participant->organization ?? '—'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Fonction</p>
                    <p class="font-medium text-gray-900"><?php echo e($participant->function ?? '—'); ?></p>
                </div>
            </div>
        </div>
        
        <!-- History -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Historique des événements</h2>
            </div>
            <div class="divide-y divide-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $participant->registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <a href="<?php echo e(route('events.show', $registration->event)); ?>" class="font-medium text-gray-900 hover:text-gray-600 transition-colors">
                                <?php echo e($registration->event->title); ?>

                            </a>
                            <p class="text-sm text-gray-500"><?php echo e($registration->event->date->format('d F Y')); ?></p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full 
                        <?php echo e($registration->status === 'present' ? 'bg-emerald-50 text-emerald-700' : ''); ?>

                        <?php echo e($registration->status === 'inscrit' ? 'bg-blue-50 text-blue-700' : ''); ?>

                        <?php echo e($registration->status === 'liste_attente' ? 'bg-amber-50 text-amber-700' : ''); ?>

                        <?php echo e($registration->status === 'absent' ? 'bg-red-50 text-red-700' : ''); ?>

                        <?php echo e($registration->status === 'annule' ? 'bg-gray-100 text-gray-600' : ''); ?>">
                        <?php echo e(ucfirst(str_replace('_', ' ', $registration->status))); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-6 py-12 text-center">
                    <p class="text-gray-500">Aucune participation enregistrée</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Danger Zone -->
    <div class="mt-8 bg-white rounded-2xl border border-red-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-red-100 bg-red-50">
            <h2 class="font-semibold text-red-900">Zone de danger</h2>
        </div>
        <div class="p-6 flex items-center justify-between">
            <div>
                <p class="font-medium text-gray-900">Supprimer ce participant</p>
                <p class="mt-1 text-sm text-gray-500">Cette action supprimera également toutes ses inscriptions.</p>
            </div>
            <form action="<?php echo e(route('participants.destroy', $participant)); ?>" method="POST" onsubmit="return confirm('Supprimer ce participant ?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-full hover:bg-red-700 transition-all">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/participants/show.blade.php ENDPATH**/ ?>