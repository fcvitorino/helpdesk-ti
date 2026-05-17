@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4"><i class="bi bi-graph-up"></i> Dashboard de Chamados</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total de Chamados</h5>
                <h2>{{ $totalTickets }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Abertos</h5>
                <h2>{{ $openTickets }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Em Andamento</h5>
                <h2>{{ $inProgressTickets }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Resolvidos / Fechados</h5>
                <h2>{{ $resolvedTickets }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-flag"></i> Chamados por Prioridade</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Prioridade</th>
                            <th>Qtd</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ticketsByPriority as $priority)
                        <tr>
                            <td>{{ ucfirst($priority->priority) }}</td>
                            <td>{{ $priority->total }}</td>
                            <td>{{ round(($priority->total / max($totalTickets,1)) * 100) }}%</td>
                        </tr>
                        @empty
                        <td><td colspan="3" class="text-center">Nenhum dado disponível</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-calendar"></i> Chamados por Mês (últimos 6 meses)</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Mês</th>
                            <th>Qtd</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ticketsByMonth as $month)
                        <tr>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('m/Y') }}</td>
                            <td>{{ $month->total }}</td>
                        </tr>
                        @empty
                        <td><td colspan="2" class="text-center">Nenhum dado disponível</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos extras APENAS para Admin e Técnico -->
@if(auth()->user()->isAdmin() || auth()->user()->isTechnician())
<div class="row mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-bar-chart"></i> Chamados por Setor</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Setor</th><th>Qtd</th><th>%</th></tr>
                    </thead>
                    <tbody>
                        @forelse($ticketsBySector as $sector)
                        <tr>
                            <td>{{ $sector->name }}</td>
                            <td>{{ $sector->total }}</td>
                            <td>{{ round(($sector->total / max($totalTickets,1)) * 100) }}%</td>
                        </tr>
                        @empty
                        <td><td colspan="3" class="text-center">Nenhum dado disponível</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-geo-alt"></i> Problemas por Local</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Local</th><th>Qtd</th></tr>
                    </thead>
                    <tbody>
                        @forelse($ticketsByLocation as $location)
                        <tr>
                            <td>{{ ucfirst($location->location) }}</td>
                            <td>{{ $location->total }}</td>
                        </tr>
                        @empty
                        <td><td colspan="2" class="text-center">Nenhum dado disponível</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
@endsection