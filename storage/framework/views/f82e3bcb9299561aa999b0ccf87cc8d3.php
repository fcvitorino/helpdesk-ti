

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Setores</h4>
        <a href="<?php echo e(route('admin.sectors.create')); ?>" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Novo Setor
        </a>
    </div>
    <div class="card-body">
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Empresa</th>
                        <th>Data Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $sectors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sector): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($sector->id); ?></td>
                        <td><?php echo e($sector->name); ?></td>
                        <td><?php echo e($sector->company->name ?? '-'); ?></td>
                        <td><?php echo e($sector->created_at->format('d/m/Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.sectors.show', $sector)); ?>" class="btn btn-sm btn-info" title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?php echo e(route('admin.sectors.edit', $sector)); ?>" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('admin.sectors.destroy', $sector)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="text-center">Nenhum setor encontrado</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php echo e($sectors->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/admin/sectors/index.blade.php ENDPATH**/ ?>