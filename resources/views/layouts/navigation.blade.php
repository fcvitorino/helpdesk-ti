<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-headset"></i> HelpDesk TI
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                
                @auth
                
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
                
                @if(!auth()->user()->isAdmin() && !auth()->user()->isTechnician())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tickets.create') }}">
                        <i class="bi bi-plus-circle"></i> Novo Chamado
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->isAdmin())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Configurações
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.companies.index') }}"><i class="bi bi-building"></i> Empresas</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.sectors.index') }}"><i class="bi bi-tags"></i> Setores</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.invites.index') }}"><i class="bi bi-envelope"></i> Convites</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i> Usuários</a></li>
                    </ul>
                </li>
                @endif
                
                @if(auth()->user()->isAdmin() || auth()->user()->isTechnician())
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
                        <li><a class="dropdown-item text-danger" href="{{ route('switch.company.reset') }}"><i class="bi bi-arrow-left"></i> Voltar para minha empresa</a></li>
                    </ul>
                </li>
                @endif
                
                <!-- ÍCONE DE NOTIFICAÇÕES COM BADGE MENOR -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5"></i>
                        <span id="notifications-badge" class="badge rounded-pill bg-danger" style="position: absolute; top: -6px; left: 14px; font-size: 9px; min-width: 14px; height: 14px; display: none; align-items: center; justify-content: center; padding: 0 3px; line-height: 1;">
                            0
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" id="notifications-list" style="width: 350px; max-height: 400px; overflow-y: auto;">
                        <li><h6 class="dropdown-header">📢 Notificações</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="text-center text-muted py-3">Carregando...</li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text">Email: {{ auth()->user()->email }}</span></li>
                        <li><span class="dropdown-item-text">Perfil: {{ ucfirst(auth()->user()->role) }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-key"></i> Alterar Senha</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Sair</button>
                            </form>
                        </li>
                    </ul>
                </li>
                
                @endauth
                
                @guest
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light px-3" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                </li>
                @endguest
                
            </ul>
        </div>
    </div>
</nav>

<style>
    body { padding-top: 70px; }
    .fixed-top { position: fixed; top: 0; right: 0; left: 0; z-index: 1030; }
    .notification-item { white-space: normal !important; padding: 10px 15px; }
    .notification-item:hover { background-color: #f8f9fa; }
</style>

<script>
    function loadNotifications() {
        fetch('{{ route("notifications.index") }}')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('notifications-list');
                const badge = document.getElementById('notifications-badge');
                const unreadCount = data.data.filter(n => !n.is_read).length;
                
                if (unreadCount > 0) {
                    badge.style.display = 'inline-flex';
                    badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                } else {
                    badge.style.display = 'none';
                }
                
                if (!data.data || data.data.length === 0) {
                    list.innerHTML = '<li class="text-center text-muted py-3">📭 Nenhuma notificação</li>';
                    return;
                }
                
                let html = '<li><h6 class="dropdown-header">📢 Notificações</h6></li><li><hr class="dropdown-divider"></li>';
                data.data.forEach(notif => {
                    const bgClass = notif.is_read ? '' : 'bg-light fw-bold';
                    let icon = '📌';
                    if (notif.type === 'new_ticket') icon = '🎫';
                    if (notif.type === 'new_comment') icon = '💬';
                    if (notif.type === 'status_change') icon = '🔄';
                    
                    const ticketUrl = '{{ url("/tickets") }}/' + notif.ticket_id;
                    
                    html += `
                        <li class="${bgClass}">
                            <a class="dropdown-item notification-item" href="#" data-id="${notif.id}" data-url="${ticketUrl}">
                                <div>
                                    <strong>${icon} ${notif.title}</strong>
                                    <small class="text-muted d-block">${notif.message}</small>
                                    <small class="text-muted">${new Date(notif.created_at).toLocaleString()}</small>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                    `;
                });
                html += '<li><div class="dropdown-footer text-center p-2"><button class="btn btn-sm btn-link" id="mark-all-read">✅ Marcar todas como lidas</button></div></li>';
                list.innerHTML = html;
                
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.dataset.id;
                        const url = this.dataset.url;
                        
                        fetch('{{ url("/notifications") }}/' + id + '/read', { 
                            method: 'POST', 
                            headers: { 
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            } 
                        })
                        .then(() => {
                            window.location.href = url;
                        });
                    });
                });
                
                const markAllBtn = document.getElementById('mark-all-read');
                if (markAllBtn) {
                    markAllBtn.addEventListener('click', function() {
                        fetch('{{ route("notifications.mark-all-read") }}', { 
                            method: 'POST', 
                            headers: { 
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            } 
                        })
                        .then(() => loadNotifications());
                    });
                }
            })
            .catch(error => {
                console.error('Erro ao carregar notificações:', error);
                document.getElementById('notifications-list').innerHTML = '<li class="text-center text-danger py-3">❌ Erro ao carregar</li>';
            });
    }
    
    document.getElementById('notificationsDropdown')?.addEventListener('click', loadNotifications);
    
    fetch('{{ route("notifications.unread-count") }}')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notifications-badge');
            if (data.count > 0) {
                badge.style.display = 'inline-flex';
                badge.textContent = data.count > 99 ? '99+' : data.count;
            }
        })
        .catch(error => console.error('Erro ao carregar contador:', error));
</script>