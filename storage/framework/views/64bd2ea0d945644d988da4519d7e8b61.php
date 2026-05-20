<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">
            <i class="bi bi-headset"></i> HelpDesk TI
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                
                <!-- SÓ MOSTRA O MENU SE O USUÁRIO ESTIVER LOGADO -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                        <i class="bi bi-graph-up"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('tickets.index')); ?>">
                        <i class="bi bi-ticket"></i> Chamados
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('tickets.create')); ?>">
                        <i class="bi bi-plus-circle"></i> Novo Chamado
                    </a>
                </li>
                
                <!-- Configurações - Dropdown (apenas para Admin) -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->check() && auth()->user()->isAdmin()): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Configurações
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('admin.companies.index')); ?>">
                                <i class="bi bi-building"></i> Empresas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('admin.sectors.index')); ?>">
                                <i class="bi bi-tags"></i> Setores
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('admin.invites.index')); ?>">
                                <i class="bi bi-envelope"></i> Convites
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('admin.users.index')); ?>">
                                <i class="bi bi-people"></i> Usuários
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <!-- Seletor de Empresa (apenas para Admin logado) -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->check() && auth()->user()->isAdmin()): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-building"></i> 
                        <?php echo e(session('selected_company_name', 'Selecionar Empresa')); ?>

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Selecionar Empresa:</h6></li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Company::where('is_active', true)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('switch.company', $company->id)); ?>">
                                <?php echo e($company->name); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('selected_company_id') == $company->id): ?>
                                    <i class="bi bi-check-circle-fill text-success float-end"></i>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?php echo e(route('switch.company.reset')); ?>">
                                <i class="bi bi-arrow-left"></i> Voltar para minha empresa
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <!-- Menu do Usuário (só se estiver logado) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?php echo e(auth()->user()->name); ?>

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text">Email: <?php echo e(auth()->user()->email); ?></span></li>
                        <li><span class="dropdown-item-text">Perfil: <?php echo e(ucfirst(auth()->user()->role)); ?></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i> Sair
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <!-- SÓ MOSTRA O BOTÃO DE LOGIN SE NÃO ESTIVER LOGADO -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light px-3" href="<?php echo e(route('login')); ?>">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
            </ul>
        </div>
    </div>
</nav><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>