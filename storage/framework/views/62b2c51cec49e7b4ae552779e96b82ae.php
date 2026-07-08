

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Auditoria de Chamados</h5>
                    <div>
                        <a href="<?php echo e(route('admin.audit.exportPdf', request()->query())); ?>" class="btn btn-sm btn-success me-2">
                            <i class="bi bi-file-pdf"></i> Exportar PDF
                        </a>
                        <span class="badge bg-light text-dark"><?php echo e($logs->total()); ?> registros</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <form method="GET" action="<?php echo e(route('admin.audit.index')); ?>" class="row g-3 mb-4">
                        <div class="col-md-2">
                            <label class="form-label">Chamado</label>
                            <select name="ticket_id" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $number): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($id); ?>" <?php echo e(request('ticket_id') == $id ? 'selected' : ''); ?>>
                                        #<?php echo e($number); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Setor</label>
                            <select name="sector_id" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sectors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($id); ?>" <?php echo e(request('sector_id') == $id ? 'selected' : ''); ?>>
                                        <?php echo e($name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Usuário</label>
                            <select name="user_id" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($id); ?>" <?php echo e(request('user_id') == $id ? 'selected' : ''); ?>>
                                        <?php echo e($name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Ação</label>
                            <select name="acao" class="form-select form-select-sm">
                                <option value="">Todas</option>
                                <option value="criado" <?php echo e(request('acao') == 'criado' ? 'selected' : ''); ?>>Criado</option>
                                <option value="atualizado" <?php echo e(request('acao') == 'atualizado' ? 'selected' : ''); ?>>Atualizado</option>
                                <option value="comentário" <?php echo e(request('acao') == 'comentário' ? 'selected' : ''); ?>>Comentário</option>
                                <option value="deletado" <?php echo e(request('acao') == 'deletado' ? 'selected' : ''); ?>>Deletado</option>
                                <option value="restaurado" <?php echo e(request('acao') == 'restaurado' ? 'selected' : ''); ?>>Restaurado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Data Início</label>
                            <input type="date" name="data_inicio" class="form-control form-control-sm" value="<?php echo e(request('data_inicio')); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Data Fim</label>
                            <input type="date" name="data_fim" class="form-control form-control-sm" value="<?php echo e(request('data_fim')); ?>">
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search"></i> Filtrar
                            </button>
                            <a href="<?php echo e(route('admin.audit.index')); ?>" class="btn btn-secondary btn-sm">
                                <i class="bi bi-eraser"></i> Limpar
                            </a>
                        </div>
                    </form>

                    <!-- Tabela de logs -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Data/Hora</th>
                                    <th>Usuário</th>
                                    <th>Ação</th>
                                    <th>Chamado</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($log->id); ?></td>
                                        <td><?php echo e($log->created_at->format('d/m/Y H:i:s')); ?></td>
                                        <td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->causer): ?>
                                                <?php echo e($log->causer->name); ?>

                                            <?php else: ?>
                                                <span class="text-muted">Sistema</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $desc = $log->description;
                                                $badge = 'secondary';
                                                if (str_contains($desc, 'criado')) $badge = 'success';
                                                elseif (str_contains($desc, 'atualizado')) $badge = 'warning';
                                                elseif (str_contains($desc, 'deletado')) $badge = 'danger';
                                                elseif (str_contains($desc, 'restaurado')) $badge = 'info';
                                                elseif (str_contains($desc, 'Comentário')) $badge = 'primary';
                                            ?>
                                            <span class="badge bg-<?php echo e($badge); ?>"><?php echo e($desc); ?></span>
                                        </td>
                                        <td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->subject && $log->subject_type == 'App\Models\Ticket'): ?>
                                                <a href="<?php echo e(route('tickets.show', $log->subject->id)); ?>">
                                                    #<?php echo e($log->subject->ticket_number ?? $log->subject->id); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $props = $log->properties;
                                            ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($props && $props->count() > 0): ?>
                                                
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($props->has('attributes') || $props->has('old')): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="collapse" data-bs-target="#log-<?php echo e($log->id); ?>">
                                                        <i class="bi bi-eye"></i> Ver alterações
                                                    </button>
                                                    <div id="log-<?php echo e($log->id); ?>" class="collapse mt-2">
                                                        <div class="card card-body bg-light p-2">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($props->has('attributes')): ?>
                                                                <strong>Novos valores:</strong>
                                                                <pre class="mb-0 small"><?php echo e(json_encode($props->get('attributes'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($props->has('old')): ?>
                                                                <strong class="mt-2">Valores anteriores:</strong>
                                                                <pre class="mb-0 small"><?php echo e(json_encode($props->get('old'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </div>

                                                <?php else: ?>
                                                    
                                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="collapse" data-bs-target="#log-<?php echo e($log->id); ?>">
                                                        <i class="bi bi-eye"></i> Ver detalhes
                                                    </button>
                                                    <div id="log-<?php echo e($log->id); ?>" class="collapse mt-2">
                                                        <div class="card card-body bg-light p-2">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $props->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <strong><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?>:</strong>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($value) || is_object($value)): ?>
                                                                    <pre class="mb-1 small"><?php echo e(json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                                                <?php else: ?>
                                                                    <span class="d-block mb-1"><?php echo e($value); ?></span>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Nenhum registro encontrado.</td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($logs->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/admin/audit/index.blade.php ENDPATH**/ ?>