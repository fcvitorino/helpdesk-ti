@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Dashboard</h2>
        </div>
    </div>
    
    <!-- Cards de estatísticas -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total de Chamados</h5>
                    <h2 class="card-text">{{ $totalTickets ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Abertos</h5>
                    <h2 class="card-text">{{ $ticketsAberto ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Em Andamento</h5>
                    <h2 class="card-text">{{ $ticketsEmAndamento ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Resolvidos</h5>
                    <h2 class="card-text">{{ $ticketsResolvido ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Linha 1: Setor (APENAS para Admin/Técnico) + Local -->
    <div class="row">
        @if($showSectorChart)
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-building"></i> Chamados por Setor
                </div>
                <div class="card-body">
                    <canvas id="chartSetor" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-geo-alt"></i> Chamados por Local
                </div>
                <div class="card-body">
                    <canvas id="chartLocal" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        @else
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-geo-alt"></i> Chamados por Local
                </div>
                <div class="card-body">
                    <canvas id="chartLocal" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Linha 2: Prioridade + Meses (sempre visível para todos) -->
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-exclamation-triangle"></i> Chamados por Prioridade
                </div>
                <div class="card-body d-flex justify-content-center">
                    <canvas id="chartPrioridade" style="max-width: 250px; max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-calendar"></i> Chamados por Mês
                </div>
                <div class="card-body">
                    <canvas id="chartMes" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($showSectorChart)
    // Gráfico por Setor (apenas para Admin/Técnico)
    const ctxSetor = document.getElementById('chartSetor').getContext('2d');
    new Chart(ctxSetor, {
        type: 'bar',
        data: {
            labels: {!! json_encode($ticketsPorSetor->pluck('name')) !!},
            datasets: [{
                label: 'Quantidade',
                data: {!! json_encode($ticketsPorSetor->pluck('total')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1,
                    ticks: { precision: 0 }
                }
            }
        }
    });
    @endif
    
    // Gráfico por Prioridade (todos)
    const ctxPrioridade = document.getElementById('chartPrioridade').getContext('2d');
    new Chart(ctxPrioridade, {
        type: 'pie',
        data: {
            labels: {!! json_encode($ticketsPorPrioridade->pluck('priority')) !!},
            datasets: [{
                data: {!! json_encode($ticketsPorPrioridade->pluck('total')) !!},
                backgroundColor: ['#28a745', '#ffc107', '#fd7e14', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percent = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percent}%)`;
                        }
                    }
                }
            }
        }
    });
    
    // Gráfico por Local (todos)
    const ctxLocal = document.getElementById('chartLocal').getContext('2d');
    new Chart(ctxLocal, {
        type: 'bar',
        data: {
            labels: {!! json_encode($ticketsPorLocal->pluck('location')) !!},
            datasets: [{
                label: 'Quantidade',
                data: {!! json_encode($ticketsPorLocal->pluck('total')) !!},
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1,
                    ticks: { precision: 0 }
                }
            }
        }
    });
    
    // Gráfico por Mês (todos)
    const ctxMes = document.getElementById('chartMes').getContext('2d');
    new Chart(ctxMes, {
        type: 'line',
        data: {
            labels: {!! json_encode($ticketsPorMes->pluck('month')) !!},
            datasets: [{
                label: 'Quantidade',
                data: {!! json_encode($ticketsPorMes->pluck('total')) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1,
                    ticks: { precision: 0 }
                }
            }
        }
    });
</script>
@endsection