

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">Detalhes da Empresa</h4>
            </div>
            <div class="card-body">
                
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td><?php echo e($company->id); ?></td>
                    </tr>
                    <tr>
                        <th>Nome</th>
                        <td><?php echo e($company->name); ?></td>
                    </tr>
                    <tr>
                        <th>CNPJ</th>
                        <td><?php echo e($company->cnpj ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Telefone</th>
                        <td><?php echo e($company->telefone ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Endereço</th>
                        <td><?php echo e($company->endereco ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($company->is_active): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inativo</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Data Cadastro</th>
                        <td><?php echo e($company->created_at->format('d/m/Y H:i')); ?></td>
                    </tr>
                </table>
                
                <a href="<?php echo e(route('admin.companies.edit', $company)); ?>" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <a href="<?php echo e(route('admin.companies.index')); ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/admin/companies/show.blade.php ENDPATH**/ ?>