

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Convites</h4>
        <a href="<?php echo e(route('admin.invites.create')); ?>" class="btn btn-light">
            <i class="bi bi-envelope-plus"></i> Novo Convite
        </a>
    </div>
    <div class="card-body">
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Empresa</th>
                        <th>Setor</th>
                        <th>Status</th>
                        <th>Expira em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $invites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($invite->id); ?></td>
                        <td><?php echo e($invite->email); ?></td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invite->role == 'admin'): ?>
                                <span class="badge bg-danger">Admin</span>
                            <?php elseif($invite->role == 'technician'): ?>
                                <span class="badge bg-info">Técnico</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Usuário</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td><?php echo e($invite->company->name ?? '-'); ?></td>
                        <td><?php echo e($invite->sector->name ?? '-'); ?></td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invite->status == 'pending'): ?>
                                <span class="badge bg-warning text-dark">Pendente</span>
                            <?php elseif($invite->status == 'accepted'): ?>
                                <span class="badge bg-success">Aceito</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Expirado</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td><?php echo e($invite->expires_at ? $invite->expires_at->format('d/m/Y') : '-'); ?></td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invite->status == 'pending'): ?>
                                <form action="<?php echo e(route('admin.invites.resend', $invite)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-primary">Reenviar</button>
                                </form>
                                <form action="<?php echo e(route('admin.invites.cancel', $invite)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                                </form>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <form action="<?php echo e(route('admin.invites.destroy', $invite)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-secondary">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="text-center">Nenhum convite encontrado</td><tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php echo e($invites->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/admin/invites/index.blade.php ENDPATH**/ ?>