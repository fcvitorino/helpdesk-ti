

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">Editar Empresa</h4>
            </div>
            <div class="card-body">
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <form method="POST" action="<?php echo e(route('admin.companies.update', $company)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" 
                               value="<?php echo e(old('name', $company->name)); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">CNPJ</label>
                        <input type="text" name="cnpj" class="form-control" 
                               value="<?php echo e(old('cnpj', $company->cnpj)); ?>" 
                               placeholder="00.000.000/0000-00">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="telefone" class="form-control" 
                               value="<?php echo e(old('telefone', $company->telefone)); ?>" 
                               placeholder="(00) 0000-0000">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Endereço</label>
                        <textarea name="endereco" rows="3" class="form-control" 
                                  placeholder="Rua, número, bairro, cidade - UF"><?php echo e(old('endereco', $company->endereco)); ?></textarea>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" 
                               value="1" <?php echo e($company->is_active ? 'checked' : ''); ?>>
                        <label class="form-check-label">Empresa Ativa</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="<?php echo e(route('admin.companies.index')); ?>" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/admin/companies/edit.blade.php ENDPATH**/ ?>