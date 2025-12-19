<?php $__env->startSection('title', 'Check-in - ' . $event->title); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-in">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="<?php echo e(route('events.show', $event)); ?>" class="hover:text-gray-900 transition-colors"><?php echo e($event->title); ?></a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900">Check-in</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Check-in</h1>
            <p class="mt-1 text-gray-500"><?php echo e($event->date->format('d F Y')); ?> • <?php echo e($event->start_time); ?> - <?php echo e($event->end_time); ?></p>
        </div>
        <a href="<?php echo e(route('events.show', $event)); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-full hover:bg-gray-200 transition-all">
            ← Retour
        </a>
    </div>
    
    <!-- Live Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-400 text-white">
            <div class="relative z-10">
                <div class="text-4xl font-bold"><?php echo e($statistics['present']); ?></div>
                <div class="mt-1 text-emerald-100 font-medium">Présents</div>
            </div>
            <div class="absolute -right-2 -bottom-2 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-400 text-white">
            <div class="relative z-10">
                <div class="text-4xl font-bold"><?php echo e($statistics['waiting']); ?></div>
                <div class="mt-1 text-blue-100 font-medium">À pointer</div>
            </div>
        </div>
        
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-red-500 to-red-400 text-white">
            <div class="relative z-10">
                <div class="text-4xl font-bold"><?php echo e($statistics['absent']); ?></div>
                <div class="mt-1 text-red-100 font-medium">Absents</div>
            </div>
        </div>
        
        <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-gray-800 to-gray-600 text-white">
            <div class="relative z-10">
                <div class="text-4xl font-bold"><?php echo e($statistics['total'] > 0 ? round(($statistics['present'] / $statistics['total']) * 100) : 0); ?>%</div>
                <div class="mt-1 text-gray-300 font-medium">Taux</div>
            </div>
        </div>
    </div>
    
    <!-- Search -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form action="<?php echo e(route('checkin.search', $event)); ?>" method="GET" class="flex gap-4">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="q" value="<?php echo e($query ?? ''); ?>" placeholder="Rechercher par nom ou email..."
                    class="w-full pl-12 pr-4 py-3.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
            </div>
            <button type="submit" class="px-6 py-3.5 text-sm font-semibold text-white bg-gray-900 rounded-xl hover:bg-gray-800 transition-all">
                Rechercher
            </button>
            <?php if(isset($query) && $query): ?>
                <a href="<?php echo e(route('checkin.index', $event)); ?>" class="px-6 py-3.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">
                    Effacer
                </a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Participants List -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Participants (<?php echo e($registrations->count()); ?>)</h2>
        </div>
        
        <?php if($registrations->count() > 0): ?>
        <div class="divide-y divide-gray-50">
            <?php $__currentLoopData = $registrations->sortBy(fn($r) => $r->status === 'present' ? 1 : 0); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center justify-between px-6 py-4 <?php echo e($registration->status === 'present' ? 'bg-emerald-50/50' : ''); ?> hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-bold 
                        <?php echo e($registration->status === 'present' ? 'bg-emerald-100 text-emerald-700' : 'bg-gradient-to-br from-gray-600 to-gray-800 text-white'); ?>">
                        <?php echo e(strtoupper(substr($registration->participant->first_name, 0, 1) . substr($registration->participant->last_name, 0, 1))); ?>

                    </div>
                    <div>
                        <p class="font-semibold text-gray-900"><?php echo e($registration->participant->full_name); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($registration->participant->email); ?></p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <?php if($registration->checked_in_at): ?>
                        <span class="text-sm text-gray-500"><?php echo e($registration->checked_in_at->format('H:i')); ?></span>
                    <?php endif; ?>
                    
                    <?php if($registration->status === 'inscrit'): ?>
                        <div class="flex gap-2">
                            <form action="<?php echo e(route('checkin.checkin', [$event, $registration])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-full hover:bg-emerald-700 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Pointer
                                </button>
                            </form>
                            <form action="<?php echo e(route('checkin.absent', [$event, $registration])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-red-500 rounded-full hover:bg-red-600 transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Absent
                                </button>
                            </form>
                        </div>
                    <?php elseif($registration->status === 'present'): ?>
                        <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-emerald-700 bg-emerald-100 rounded-full">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Présent
                        </span>
                    <?php elseif($registration->status === 'absent'): ?>
                        <form action="<?php echo e(route('checkin.checkin', [$event, $registration])); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200 transition-all">
                                Marquer présent
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="px-6 py-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <p class="text-gray-500">Aucun participant trouvé</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/checkin/index.blade.php ENDPATH**/ ?>