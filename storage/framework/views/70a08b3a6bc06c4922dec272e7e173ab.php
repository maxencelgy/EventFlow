<?php $__env->startSection('title', 'Événements'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-in">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Événements</h1>
            <p class="mt-1 text-gray-500"><?php echo e($events->total()); ?> événement(s) au total</p>
        </div>
        <a href="<?php echo e(route('events.create')); ?>" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-800 transition-all duration-300 hover:shadow-lg hover:shadow-gray-900/25 hover:scale-[1.02] active:scale-[0.98]">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nouvel événement
        </a>
    </div>
    
    <!-- Events Grid -->
    <?php if($events->count() > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-gray-200/50 hover:-translate-y-1">
            <!-- Event Header -->
            <div class="p-6 pb-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full <?php echo e($event->status === 'published' ? 'bg-emerald-50 text-emerald-700' : ($event->status === 'draft' ? 'bg-gray-100 text-gray-600' : 'bg-red-50 text-red-700')); ?>">
                            <?php echo e($event->status === 'published' ? '● Publié' : ($event->status === 'draft' ? 'Brouillon' : 'Annulé')); ?>

                        </span>
                        <h3 class="mt-3 text-lg font-semibold text-gray-900 truncate group-hover:text-gray-700">
                            <a href="<?php echo e(route('events.show', $event)); ?>"><?php echo e($event->title); ?></a>
                        </h3>
                    </div>
                </div>
                
                <div class="mt-4 space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <?php echo e($event->date->format('d M Y')); ?>

                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <?php echo e($event->location); ?>

                    </div>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="px-6 pb-4">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="font-medium text-gray-900"><?php echo e($event->confirmed_count); ?>/<?php echo e($event->max_participants); ?></span>
                    <span class="text-gray-500"><?php echo e(round(($event->confirmed_count / $event->max_participants) * 100)); ?>%</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-gray-800 to-gray-600 rounded-full transition-all duration-500" style="width: <?php echo e(min(100, round(($event->confirmed_count / $event->max_participants) * 100))); ?>%"></div>
                </div>
                <?php if($event->waiting_count > 0): ?>
                    <p class="mt-2 text-xs text-amber-600 font-medium">+<?php echo e($event->waiting_count); ?> en liste d'attente</p>
                <?php endif; ?>
            </div>
            
            <!-- Actions -->
            <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50 flex items-center gap-2">
                <a href="<?php echo e(route('events.show', $event)); ?>" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                    Voir
                </a>
                <?php if($event->date->isToday() || $event->status === 'published'): ?>
                    <a href="<?php echo e(route('checkin.index', $event)); ?>" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Check-in
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    
    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        <?php echo e($events->links()); ?>

    </div>
    <?php else: ?>
    <!-- Empty State -->
    <div class="text-center py-20">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
            <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Aucun événement</h3>
        <p class="mt-2 text-gray-500 max-w-sm mx-auto">Commencez par créer votre premier événement pour gérer vos inscriptions.</p>
        <a href="<?php echo e(route('events.create')); ?>" class="mt-6 inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-800 transition-all">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Créer un événement
        </a>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/events/index.blade.php ENDPATH**/ ?>