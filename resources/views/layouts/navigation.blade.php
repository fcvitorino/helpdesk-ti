<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-headset"></i> HelpDesk TI
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="bi bi-graph-up"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tickets.index') }}">
                        <i class="bi bi-ticket"></i> Chamados
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tickets.create') }}">
                        <i class="bi bi-plus-circle"></i> Novo Chamado
                    </a>
                </li>
                
                <!-- Configurações - Dropdown (apenas para Admin) -->
                @if(auth()->user()->isAdmin())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Configurações
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.companies.index') }}">
                                <i class="bi bi-building"></i> Empresas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.sectors.index') }}">
                                <i class="bi bi-tags"></i> Setores
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.invites.index') }}">
                                <i class="bi bi-envelope"></i> Convites
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people"></i> Usuários
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                
                <!-- Seletor de Empresa (apenas para Admin) -->
                @if(auth()->user()->isAdmin())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-building"></i> 
                        {{ session('selected_company_name', 'Selecionar Empresa') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Selecionar Empresa:</h6></li>
                        @foreach(\App\Models\Company::where('is_active', true)->get() as $company)
                        <li>
                            <a class="dropdown-item" href="{{ route('switch.company', $company->id) }}">
                                {{ $company->name }}
                                @if(session('selected_company_id') == $company->id)
                                    <i class="bi bi-check-circle-fill text-success float-end"></i>
                                @endif
                            </a>
                        </li>
                        @endforeach
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('switch.company.reset') }}">
                                <i class="bi bi-arrow-left"></i> Voltar para minha empresa
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                
                <!-- Menu do Usuário -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text">Email: {{ auth()->user()->email }}</span></li>
                        <li><span class="dropdown-item-text">Perfil: {{ ucfirst(auth()->user()->role) }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i> Sair
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>