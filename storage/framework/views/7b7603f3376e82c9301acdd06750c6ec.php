

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-ticket"></i> Meus Chamados</h4>
        <a href="<?php echo e(route('tickets.create')); ?>" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Novo Chamado
        </a>
    </div>
    <div class="card-body">
        
        <!-- Busca por Nº Chamado ou Título -->
        <div class="row mb-3">
            <div class="col-md-4">
                <form method="GET" action="<?php echo e(route('tickets.index')); ?>" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Buscar por Nº Chamado ou Título..." value="<?php echo e(request('search')); ?>">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
        
        <!-- Filtros -->
        <form method="GET" action="<?php echo e(route('tickets.index')); ?>" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Filtrar por Status</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="aberto" <?php echo e(request('status') == 'aberto' ? 'selected' : ''); ?>>Aberto</option>
                        <option value="em_andamento" <?php echo e(request('status') == 'em_andamento' ? 'selected' : ''); ?>>Em Andamento</option>
                        <option value="resolvido" <?php echo e(request('status') == 'resolvido' ? 'selected' : ''); ?>>Resolvido</option>
                        <option value="fechado" <?php echo e(request('status') == 'fechado' ? 'selected' : ''); ?>>Fechado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filtrar por Prioridade</label>
                    <select name="priority" class="form-select">
                        <option value="">Todas</option>
                        <option value="baixa" <?php echo e(request('priority') == 'baixa' ? 'selected' : ''); ?>>Baixa</option>
                        <option value="normal" <?php echo e(request('priority') == 'normal' ? 'selected' : ''); ?>>Normal</option>
                        <option value="urgente" <?php echo e(request('priority') == 'urgente' ? 'selected' : ''); ?>>Urgente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filtrar por Setor</label>
                    <select name="sector_id" class="form-select">
                        <option value="">Todos</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sectors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sector): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sector->id); ?>" <?php echo e(request('sector_id') == $sector->id ? 'selected' : ''); ?>>
                                <?php echo e($sector->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    <a href="<?php echo e(route('tickets.index')); ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Limpar
                    </a>
                </div>
            </div>
        </form>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tickets->isEmpty()): ?>
            <div class="text-center py-4">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="mt-2">Nenhum chamado encontrado.</p>
                <a href="<?php echo e(route('tickets.create')); ?>" class="btn btn-primary">
                    Abrir primeiro chamado
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nº Chamado</th>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Setor</th>
                            <th>Prioridade</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($ticket->ticket_number); ?></strong></td>
                            <td>#<?php echo e($ticket->id); ?></td>
                            <td><?php echo e($ticket->title); ?></td>
                            <td><?php echo e($ticket->sector->name ?? 'N/A'); ?></td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket->priority == 'urgente'): ?>
                                    <span class="badge bg-danger">Urgente</span>
                                <?php elseif($ticket->priority == 'normal'): ?>
                                    <span class="badge bg-primary">Normal</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Baixa</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket->status == 'aberto'): ?>
                                    <span class="badge bg-warning">Aberto</span>
                                <?php elseif($ticket->status == 'em_andamento'): ?>
                                    <span class="badge bg-info">Em Andamento</span>
                                <?php elseif($ticket->status == 'resolvido'): ?>
                                    <span class="badge bg-success">Resolvido</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Fechado</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td><?php echo e($ticket->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <a href="<?php echo e(route('tickets.show', $ticket)); ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($tickets->links()); ?>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/tickets/index.blade.php ENDPATH**/ ?>