

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-ticket-detailed"></i> Chamado #<?php echo e($ticket->ticket_number); ?>

                    </h4>
                    <a href="<?php echo e(route('tickets.index')); ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
                <div class="card-body">
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <div class="row">
                        <!-- COLUNA ESQUERDA - CONTEÚDO PRINCIPAL -->
                        <div class="col-md-8">
                            
                            <!-- Título -->
                            <div class="mb-4">
                                <h5 class="fw-bold"><?php echo e($ticket->title); ?></h5>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> Abertura: <?php echo e($ticket->created_at->format('d/m/Y H:i')); ?>

                                </small>
                            </div>
                            
                            <!-- Descrição -->
                            <div class="mb-4">
                                <strong><i class="bi bi-chat-left-text"></i> Descrição:</strong>
                                <div class="border rounded p-3 bg-light mt-2">
                                    <?php echo e($ticket->description); ?>

                                </div>
                            </div>
                            
                            <!-- SEÇÃO DE COMENTÁRIOS ESTILIZADA -->
                            <div class="mt-5">
                                <div class="card bg-light border-0 shadow-sm">
                                    <div class="card-header bg-secondary text-white">
                                        <i class="bi bi-chat-dots-fill"></i> Chat do Chamado
                                        <span class="badge bg-light text-dark float-end"><?php echo e($replies->count()); ?> mensagens</span>
                                    </div>
                                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                        
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <?php
                                                $isMe = $reply->user_id == auth()->id();
                                                $isSupport = $reply->is_support;
                                            ?>
                                            
                                            <div class="mb-4 <?php echo e($isMe || $isSupport ? 'text-end' : 'text-start'); ?>">
                                                <div class="d-inline-block" style="max-width: 75%;">
                                                    <small class="text-muted d-block mb-1 <?php echo e($isMe || $isSupport ? 'text-end' : 'text-start'); ?>">
                                                        <strong><?php echo e($reply->user->name); ?></strong> • <?php echo e($reply->created_at->format('d/m/Y H:i')); ?>

                                                    </small>
                                                    <div class="rounded-3 p-3 shadow-sm <?php echo e($isMe || $isSupport ? 'bg-primary bg-opacity-10 text-dark' : 'bg-white border'); ?>">
                                                        <p class="mb-2"><?php echo e($reply->message); ?></p>
                                                        
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($reply->attachments && $reply->attachments->count() > 0): ?>
                                                            <div class="mt-2">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $reply->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <a href="<?php echo e(asset('storage/' . $attachment->path)); ?>" target="_blank" 
                                                                       class="btn btn-sm btn-outline-secondary">
                                                                        <i class="bi bi-paperclip"></i> <?php echo e($attachment->original_name); ?>

                                                                    </a>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </div>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="text-center text-muted py-5">
                                                <i class="bi bi-chat-square-text fs-1"></i>
                                                <p class="mt-3 mb-0">Nenhuma mensagem ainda.</p>
                                                <small>Seja o primeiro a comentar!</small>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        
                                    </div>
                                    
                                    <!-- 🔒 FORMULÁRIO CONDICIONAL (bloqueado para resolvido/fechado) -->
                                    <div class="card-footer bg-white">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket->status == 'resolvido' || $ticket->status == 'fechado'): ?>
                                            <div class="alert alert-warning text-center mb-0">
                                                <i class="bi bi-lock-fill"></i> 
                                                <strong>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket->status == 'resolvido'): ?>
                                                        ✅ Este chamado está RESOLVIDO.
                                                    <?php else: ?>
                                                        🔒 Este chamado está FECHADO.
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </strong>
                                                Não é possível adicionar novos comentários.
                                            </div>
                                        <?php else: ?>
                                            <form method="POST" action="<?php echo e(route('tickets.comments', $ticket)); ?>" enctype="multipart/form-data">
                                                <?php echo csrf_field(); ?>
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <textarea name="message" rows="2" class="form-control" 
                                                                  placeholder="Digite sua mensagem..." required></textarea>
                                                    </div>
                                                    <div class="col-8">
                                                        <input type="file" name="attachment" class="form-control form-control-sm">
                                                        <small class="text-muted">Anexar arquivo (imagem, PDF, até 5MB)</small>
                                                    </div>
                                                    <div class="col-4">
                                                        <button type="submit" class="btn btn-primary w-100">
                                                            <i class="bi bi-send"></i> Enviar
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <!-- COLUNA DIREITA - INFORMAÇÕES DO CHAMADO -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="fw-bold"><i class="bi bi-info-circle"></i> Informações</h6>
                                    <hr>
                                    
                                    <div class="mb-3">
                                        <strong>Status:</strong><br>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket->status == 'aberto'): ?>
                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Aberto</span>
                                        <?php elseif($ticket->status == 'em_andamento'): ?>
                                            <span class="badge bg-primary"><i class="bi bi-gear"></i> Em Andamento</span>
                                        <?php elseif($ticket->status == 'resolvido'): ?>
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Resolvido</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i class="bi bi-archive"></i> Fechado</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Prioridade:</strong><br>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket->priority == 'urgente'): ?>
                                            <span class="badge bg-danger">🔴 Urgente</span>
                                        <?php elseif($ticket->priority == 'alta'): ?>
                                            <span class="badge bg-warning text-dark">🟠 Alta</span>
                                        <?php elseif($ticket->priority == 'normal'): ?>
                                            <span class="badge bg-info text-dark">🟢 Normal</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">🔵 Baixa</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Local:</strong><br>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ticket->location == 'navegador'): ?> 🌐 Navegador
                                        <?php elseif($ticket->location == 'rede'): ?> 🔌 Rede
                                        <?php elseif($ticket->location == 'hardware'): ?> 💻 Hardware
                                        <?php elseif($ticket->location == 'impressora'): ?> 🖨️ Impressora
                                        <?php elseif($ticket->location == 'perifericos'): ?> 🖱️ Periféricos
                                        <?php elseif($ticket->location == 'monitor'): ?> 🖥️ Monitor
                                        <?php else: ?> 📦 Outros
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Solicitante:</strong><br>
                                        <i class="bi bi-person-circle"></i> <?php echo e($ticket->user->name ?? '-'); ?>

                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Setor:</strong><br>
                                        <i class="bi bi-building"></i> <?php echo e($ticket->sector->name ?? '-'); ?>

                                    </div>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdmin() || auth()->user()->isTechnician()): ?>
                                        <hr>
                                        <form method="POST" action="<?php echo e(route('tickets.status', $ticket)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <div class="mb-3">
                                                <label class="form-label">Alterar Status</label>
                                                <select name="status" class="form-select form-select-sm">
                                                    <option value="aberto" <?php echo e($ticket->status == 'aberto' ? 'selected' : ''); ?>>🟡 Aberto</option>
                                                    <option value="em_andamento" <?php echo e($ticket->status == 'em_andamento' ? 'selected' : ''); ?>>🔵 Em Andamento</option>
                                                    <option value="resolvido" <?php echo e($ticket->status == 'resolvido' ? 'selected' : ''); ?>>✅ Resolvido</option>
                                                    <option value="fechado" <?php echo e($ticket->status == 'fechado' ? 'selected' : ''); ?>>⚪ Fechado</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-pencil"></i> Atualizar Status
                                            </button>
                                        </form>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/tickets/show.blade.php ENDPATH**/ ?>