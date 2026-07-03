

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="bi bi-building"></i> Selecione uma Empresa
                    </h3>
                    <small>Você precisa selecionar uma empresa para continuar</small>
                </div>
                <div class="card-body p-4">
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('warning')): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo e(session('warning')); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <form method="POST" action="<?php echo e(route('company.select.store')); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Empresa</label>
                            <select name="company_id" class="form-select form-select-lg" required>
                                <option value="">Selecione uma empresa...</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($company->id); ?>">
                                        <?php echo e($company->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-check-circle"></i> Acessar Sistema
                        </button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Você pode trocar de empresa a qualquer momento no menu superior.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/company/select.blade.php ENDPATH**/ ?>