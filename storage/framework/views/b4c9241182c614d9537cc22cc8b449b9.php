<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Auditoria</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 10px; 
            padding: 10px;
        }
        h1 { 
            text-align: center; 
            font-size: 16px; 
            margin-bottom: 5px; 
        }
        .subtitle { 
            text-align: center; 
            font-size: 11px; 
            color: #555; 
            margin-bottom: 15px; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: auto;
        }
        th { 
            background-color: #0d6efd; 
            color: #fff; 
            padding: 5px 6px; 
            text-align: left; 
            font-size: 10px;
        }
        td { 
            padding: 4px 6px; 
            border-bottom: 1px solid #ddd; 
            vertical-align: top;
            font-size: 9px;
            word-wrap: break-word;
            word-break: break-word;
            white-space: pre-wrap;
        }
        .badge { 
            display: inline-block; 
            padding: 1px 5px; 
            border-radius: 3px; 
            font-size: 8px; 
            font-weight: bold; 
        }
        .badge-success { background: #28a745; color: #fff; }
        .badge-warning { background: #ffc107; color: #000; }
        .badge-danger { background: #dc3545; color: #fff; }
        .badge-info { background: #17a2b8; color: #fff; }
        .badge-primary { background: #0d6efd; color: #fff; }
        .badge-secondary { background: #6c757d; color: #fff; }
        .small-text { font-size: 8px; color: #555; }
        .footer { 
            text-align: center; 
            font-size: 9px; 
            color: #888; 
            margin-top: 15px; 
            border-top: 1px solid #ddd; 
            padding-top: 8px; 
        }
        .detail-box { 
            background: #f8f9fa; 
            padding: 4px 6px; 
            border-radius: 3px; 
            margin-top: 2px; 
            font-size: 8px; 
            word-wrap: break-word;
            word-break: break-word;
            white-space: pre-wrap;
            max-width: 100%;
        }
        .detail-box strong {
            font-size: 8px;
        }
        /* Coluna de detalhes com largura automática */
        td:last-child {
            max-width: 200px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <h1>📋 Relatório de Auditoria</h1>
    <div class="subtitle">
        Gerado em <?php echo e(now()->format('d/m/Y H:i')); ?> - Total de registros: <?php echo e($logs->count()); ?>

    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:12%;">Data/Hora</th>
                <th style="width:12%;">Usuário</th>
                <th style="width:12%;">Ação</th>
                <th style="width:10%;">Chamado</th>
                <th style="width:49%;">Detalhes</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($log->id); ?></td>
                    <td><?php echo e($log->created_at->format('d/m/Y H:i')); ?></td>
                    <td><?php echo e($log->causer->name ?? 'Sistema'); ?></td>
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
                        <span class="badge badge-<?php echo e($badge); ?>"><?php echo e($desc); ?></span>
                    </td>
                    <td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->subject && $log->subject_type == 'App\Models\Ticket'): ?>
                            #<?php echo e($log->subject->ticket_number ?? $log->subject->id); ?>

                        <?php else: ?>
                            —
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td>
                        <?php $props = $log->properties; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($props && $props->count() > 0): ?>
                            <div class="detail-box">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($props->has('attributes') || $props->has('old')): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($props->has('attributes')): ?>
                                        <strong>Novos:</strong> <?php echo e(json_encode($props->get('attributes'), JSON_UNESCAPED_UNICODE)); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($props->has('old')): ?>
                                        <br><strong>Antigos:</strong> <?php echo e(json_encode($props->get('old'), JSON_UNESCAPED_UNICODE)); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php else: ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $props->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <strong><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?>:</strong>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($value) || is_object($value)): ?>
                                            <?php echo e(json_encode($value, JSON_UNESCAPED_UNICODE)); ?>

                                        <?php else: ?>
                                            <?php echo e($value); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?><br><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php else: ?>
                            —
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #888;">Nenhum registro encontrado.</td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Gerado pelo sistema HelpDesk TI em <?php echo e(now()->format('d/m/Y H:i:s')); ?>

    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/admin/audit/pdf.blade.php ENDPATH**/ ?>