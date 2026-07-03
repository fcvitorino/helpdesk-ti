

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Abrir Chamado</h4>
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
                    
                    <form method="POST" action="<?php echo e(route('tickets.store')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Título do Chamado <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('title')); ?>" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Local do Problema <span class="text-danger">*</span></label>
                            <select name="location" class="form-select <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Selecione...</option>
                                <option value="navegador" <?php echo e(old('location') == 'navegador' ? 'selected' : ''); ?>>🌐 Navegador</option>
                                <option value="rede" <?php echo e(old('location') == 'rede' ? 'selected' : ''); ?>>🔌 Rede</option>
                                <option value="hardware" <?php echo e(old('location') == 'hardware' ? 'selected' : ''); ?>>💻 Hardware</option>
                                <option value="impressora" <?php echo e(old('location') == 'impressora' ? 'selected' : ''); ?>>🖨️ Impressora</option>
                                <option value="perifericos" <?php echo e(old('location') == 'perifericos' ? 'selected' : ''); ?>>🖱️ Periféricos</option>
                                <option value="monitor" <?php echo e(old('location') == 'monitor' ? 'selected' : ''); ?>>🖥️ Monitor</option>
                                <option value="outros" <?php echo e(old('location') == 'outros' ? 'selected' : ''); ?>>📦 Outros</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Prioridade <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="baixa" <?php echo e(old('priority') == 'baixa' ? 'selected' : ''); ?>>🔵 Baixa</option>
                                <option value="normal" <?php echo e(old('priority') == 'normal' ? 'selected' : ''); ?>>🟢 Normal</option>
                                <option value="alta" <?php echo e(old('priority') == 'alta' ? 'selected' : ''); ?>>🟠 Alta</option>
                                <option value="urgente" <?php echo e(old('priority') == 'urgente' ? 'selected' : ''); ?>>🔴 Urgente</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Descrição do Problema <span class="text-danger">*</span></label>
                            <textarea name="description" rows="5" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      placeholder="Descreva detalhadamente o problema..." required><?php echo e(old('description')); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Anexar Arquivos (opcional)</label>
                            <input type="file" name="attachments[]" class="form-control" multiple>
                            <small class="text-muted">Você pode anexar imagens, PDF ou documentos até 5MB</small>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('tickets.index')); ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Abrir Chamado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/tickets/create.blade.php ENDPATH**/ ?>