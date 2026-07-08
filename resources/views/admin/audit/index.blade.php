@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Auditoria de Chamados</h5>
                    <div>
                        <a href="{{ route('admin.audit.exportPdf', request()->query()) }}" class="btn btn-sm btn-success me-2">
                            <i class="bi bi-file-pdf"></i> Exportar PDF
                        </a>
                        <span class="badge bg-light text-dark">{{ $logs->total() }} registros</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('admin.audit.index') }}" class="row g-3 mb-4">
                        <div class="col-md-2">
                            <label class="form-label">Chamado</label>
                            <select name="ticket_id" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                @foreach($tickets as $id => $number)
                                    <option value="{{ $id }}" {{ request('ticket_id') == $id ? 'selected' : '' }}>
                                        #{{ $number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Setor</label>
                            <select name="sector_id" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                @foreach($sectors as $id => $name)
                                    <option value="{{ $id }}" {{ request('sector_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Usuário</label>
                            <select name="user_id" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Ação</label>
                            <select name="acao" class="form-select form-select-sm">
                                <option value="">Todas</option>
                                <option value="criado" {{ request('acao') == 'criado' ? 'selected' : '' }}>Criado</option>
                                <option value="atualizado" {{ request('acao') == 'atualizado' ? 'selected' : '' }}>Atualizado</option>
                                <option value="comentário" {{ request('acao') == 'comentário' ? 'selected' : '' }}>Comentário</option>
                                <option value="deletado" {{ request('acao') == 'deletado' ? 'selected' : '' }}>Deletado</option>
                                <option value="restaurado" {{ request('acao') == 'restaurado' ? 'selected' : '' }}>Restaurado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Data Início</label>
                            <input type="date" name="data_inicio" class="form-control form-control-sm" value="{{ request('data_inicio') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Data Fim</label>
                            <input type="date" name="data_fim" class="form-control form-control-sm" value="{{ request('data_fim') }}">
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search"></i> Filtrar
                            </button>
                            <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary btn-sm">
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
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            @if($log->causer)
                                                {{ $log->causer->name }}
                                            @else
                                                <span class="text-muted">Sistema</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $desc = $log->description;
                                                $badge = 'secondary';
                                                if (str_contains($desc, 'criado')) $badge = 'success';
                                                elseif (str_contains($desc, 'atualizado')) $badge = 'warning';
                                                elseif (str_contains($desc, 'deletado')) $badge = 'danger';
                                                elseif (str_contains($desc, 'restaurado')) $badge = 'info';
                                                elseif (str_contains($desc, 'Comentário')) $badge = 'primary';
                                            @endphp
                                            <span class="badge bg-{{ $badge }}">{{ $desc }}</span>
                                        </td>
                                        <td>
                                            @if($log->subject && $log->subject_type == 'App\Models\Ticket')
                                                <a href="{{ route('tickets.show', $log->subject->id) }}">
                                                    #{{ $log->subject->ticket_number ?? $log->subject->id }}
                                                </a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $props = $log->properties;
                                            @endphp

                                            @if($props && $props->count() > 0)
                                                {{-- Verifica se é um log de atualização (com attributes e old) --}}
                                                @if($props->has('attributes') || $props->has('old'))
                                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="collapse" data-bs-target="#log-{{ $log->id }}">
                                                        <i class="bi bi-eye"></i> Ver alterações
                                                    </button>
                                                    <div id="log-{{ $log->id }}" class="collapse mt-2">
                                                        <div class="card card-body bg-light p-2">
                                                            @if($props->has('attributes'))
                                                                <strong>Novos valores:</strong>
                                                                <pre class="mb-0 small">{{ json_encode($props->get('attributes'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                            @endif
                                                            @if($props->has('old'))
                                                                <strong class="mt-2">Valores anteriores:</strong>
                                                                <pre class="mb-0 small">{{ json_encode($props->get('old'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                            @endif
                                                        </div>
                                                    </div>

                                                @else
                                                    {{-- Log manual (ex: comentário, anexo, status) --}}
                                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="collapse" data-bs-target="#log-{{ $log->id }}">
                                                        <i class="bi bi-eye"></i> Ver detalhes
                                                    </button>
                                                    <div id="log-{{ $log->id }}" class="collapse mt-2">
                                                        <div class="card card-body bg-light p-2">
                                                            @foreach($props->toArray() as $key => $value)
                                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                                @if(is_array($value) || is_object($value))
                                                                    <pre class="mb-1 small">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                                @else
                                                                    <span class="d-block mb-1">{{ $value }}</span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Nenhum registro encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $logs->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection