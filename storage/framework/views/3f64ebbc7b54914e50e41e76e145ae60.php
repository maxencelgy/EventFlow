<?php $__env->startSection('title', 'Statistiques - ' . $event->title); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-in">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?php echo e(route('events.index')); ?>" class="hover:text-gray-900">Événements</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <a href="<?php echo e(route('events.show', $event)); ?>" class="hover:text-gray-900"><?php echo e($event->title); ?></a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-gray-900">Statistiques</span>
    </nav>
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Statistiques</h1>
            <p class="mt-1 text-gray-500"><?php echo e($event->title); ?></p>
        </div>
        <a href="<?php echo e(route('events.registrations.index', $event)); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-full hover:bg-gray-200 transition-all">
            ← Retour
        </a>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
            <div class="text-4xl font-bold text-gray-900"><?php echo e($statistics['total_registrations']); ?></div>
            <div class="mt-1 text-sm text-gray-500">Total inscriptions</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
            <div class="text-4xl font-bold text-blue-600"><?php echo e($statistics['confirmed']); ?></div>
            <div class="mt-1 text-sm text-gray-500">Confirmés</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
            <div class="text-4xl font-bold text-emerald-600"><?php echo e($statistics['present']); ?></div>
            <div class="mt-1 text-sm text-gray-500">Présents</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
            <div class="text-4xl font-bold text-amber-600"><?php echo e($statistics['waiting_list']); ?></div>
            <div class="mt-1 text-sm text-gray-500">Liste d'attente</div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Rates -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Indicateurs clés</h2>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Taux de remplissage</span>
                        <span class="text-sm font-semibold text-gray-900"><?php echo e($statistics['fill_rate']); ?>%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-600 to-blue-400 rounded-full transition-all duration-500" style="width: <?php echo e(min(100, $statistics['fill_rate'])); ?>%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Taux de présence</span>
                        <span class="text-sm font-semibold text-gray-900"><?php echo e($statistics['attendance_rate']); ?>%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-full transition-all duration-500" style="width: <?php echo e(min(100, $statistics['attendance_rate'])); ?>%"></div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-100 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Capacité</span>
                        <span class="font-semibold text-gray-900"><?php echo e($statistics['capacity']); ?> places</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Disponibles</span>
                        <span class="font-semibold text-gray-900"><?php echo e($statistics['available_spots']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Annulations</span>
                        <span class="font-semibold text-gray-900"><?php echo e($statistics['cancelled']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Absents</span>
                        <span class="font-semibold text-gray-900"><?php echo e($statistics['absent']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- By Organization -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Par organisation</h2>
            </div>
            <?php if(count($statistics['by_organization']) > 0): ?>
            <div class="divide-y divide-gray-50">
                <?php $__currentLoopData = $statistics['by_organization']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $org => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-gray-700"><?php echo e($org); ?></span>
                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                        <?php echo e($count); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div class="px-6 py-12 text-center">
                <p class="text-gray-500">Aucune donnée d'organisation</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/registrations/statistics.blade.php ENDPATH**/ ?>