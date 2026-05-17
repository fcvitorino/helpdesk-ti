

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-people"></i> Usuários</h4>
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Novo Usuário
        </a>
    </div>
    <div class="card-body">
        
        <!-- MENSAGENS DE SUCESSO OU ERRO -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- FORMULÁRIO DE FILTROS -->
        <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" class="mb-4">
            <div class="row g-3">
                
                <!-- CAMPO DE PESQUISA -->
                <div class="col-md-4">
                    <label class="form-label">Pesquisar</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Digite nome ou email..." 
                           value="<?php echo e(request('search')); ?>">
                </div>
                
                <!-- FILTRO POR PERFIL -->
                <div class="col-md-3">
                    <label class="form-label">Perfil</label>
                    <select name="role" class="form-select">
                        <option value="">Todos</option>
                        <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Administrador</option>
                        <option value="technician" <?php echo e(request('role') == 'technician' ? 'selected' : ''); ?>>Técnico</option>
                        <option value="user" <?php echo e(request('role') == 'user' ? 'selected' : ''); ?>>Usuário</option>
                    </select>
                </div>
                
                <!-- FILTRO POR STATUS - É AQUI QUE VOCÊ SELECIONA INATIVOS! -->
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>✅ Ativos</option>
                        <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>❌ Inativos</option>
                    </select>
                </div>
                
                <!-- BOTÕES -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        🔍 Filtrar
                    </button>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">
                        🗑️ Limpar
                    </a>
                </div>
            </div>
        </form>

        <!-- CONTADOR DE RESULTADOS -->
        <div class="mb-3">
            <strong>📊 Total encontrado: <?php echo e($users->total()); ?> usuário(s)</strong>
        </div>

        <!-- TABELA DE USUÁRIOS -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Empresa</th>
                        <th>Setor</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th>Data Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="<?php echo e($user->trashed() ? 'table-danger' : ''); ?>">
                        <td><?php echo e($user->id); ?></td>
                        <td>
                            <?php echo e($user->name); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->trashed()): ?>
                                <span class="badge bg-danger">(Inativo)</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td><?php echo e($user->email); ?></td>
                        <td><?php echo e($user->company->name ?? '-'); ?></td>
                        <td><?php echo e($user->sector->name ?? '-'); ?></td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role == 'admin'): ?>
                                <span class="badge bg-danger">Admin</span>
                            <?php elseif($user->role == 'technician'): ?>
                                <span class="badge bg-info">Técnico</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Usuário</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->trashed()): ?>
                                <span class="badge bg-danger">❌ Inativo</span>
                            <?php else: ?>
                                <span class="badge bg-success">✅ Ativo</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td><?php echo e($user->created_at->format('d/m/Y H:i')); ?></td>
                        <td>
                            <!-- BOTÃO EDITAR (só aparece se estiver ativo) -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$user->trashed()): ?>
                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-sm btn-warning" title="Editar">
                                    ✏️
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            
                            <!-- BOTÃO REATIVAR (aparece se estiver inativo) -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->trashed()): ?>
                                <form action="<?php echo e(route('admin.users.activate', $user->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-sm btn-success" title="Reativar" onclick="return confirm('Reativar este usuário?')">
                                        🔄 Reativar
                                    </button>
                                </form>
                            <?php else: ?>
                                <!-- BOTÃO DESATIVAR (aparece se estiver ativo) -->
                                <form action="<?php echo e(route('admin.users.deactivate', $user->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-sm btn-warning" title="Desativar" onclick="return confirm('Desativar este usuário?')">
                                        ⚠️ Desativar
                                    </button>
                                </form>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            
                            <!-- BOTÃO EXCLUIR PERMANENTEMENTE (só aparece se estiver inativo) -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->id !== auth()->id() && $user->trashed()): ?>
                                <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" title="Excluir Permanentemente" onclick="return confirm('Excluir permanentemente? Isso NÃO pode ser desfeito!')">
                                        🗑️ Excluir
                                    </button>
                                </form>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                📭 Nenhum usuário encontrado
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- LINKS DE PAGINAÇÃO -->
        <div class="mt-3">
            <?php echo e($users->links()); ?>

        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/admin/users/index.blade.php ENDPATH**/ ?>