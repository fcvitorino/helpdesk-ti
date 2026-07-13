<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Chamados</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            padding: 10px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 2px;
            color: #0d6efd;
        }
        .subtitle {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-bottom: 15px;
        }
        .periodo {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            color: #888;
            margin-top: 15px;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th {
            background-color: #0d6efd;
            color: #fff;
            padding: 4px 6px;
            text-align: left;
            font-size: 9px;
        }
        td {
            padding: 4px 6px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
        }
        .badge-success { background: #28a745; color: #fff; }
        .badge-warning { background: #ffc107; color: #000; }
        .badge-danger { background: #dc3545; color: #fff; }
        .badge-info { background: #17a2b8; color: #fff; }
        .badge-secondary { background: #6c757d; color: #fff; }
        .badge-primary { background: #0d6efd; color: #fff; }
        .badge-urgente { background: #dc3545; color: #fff; }
        .badge-alta { background: #fd7e14; color: #fff; }
        .badge-normal { background: #ffc107; color: #000; }
        .badge-baixa { background: #28a745; color: #fff; }
        
        .stat-box {
            display: inline-block;
            padding: 6px 10px;
            margin: 3px;
            border-radius: 4px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            font-size: 9px;
            text-align: center;
            min-width: 80px;
        }
        .stat-box strong {
            display: block;
            font-size: 14px;
            color: #0d6efd;
        }
        .stat-box .label {
            font-size: 8px;
            color: #555;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 10px;
        }
        .col {
            flex: 1;
            min-width: 80px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #0d6efd;
            margin-top: 12px;
            margin-bottom: 5px;
            border-bottom: 1px solid #0d6efd;
            padding-bottom: 3px;
        }
        .text-center {
            text-align: center;
        }
        .mt-2 {
            margin-top: 10px;
        }
        .mb-2 {
            margin-bottom: 10px;
        }
        .total-geral {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            background: #e7f3ff;
            border-radius: 4px;
            margin: 10px 0;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        @media print {
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <h1>📊 VitDesk - Relatório de Chamados</h1>
    <div class="subtitle">
        Sistema de Gestão de Chamados
    </div>
    <div class="periodo">
        Período: <strong>{{ $data_inicio }}</strong> a <strong>{{ $data_fim }}</strong>
    </div>

    <div class="total-geral">
        📌 Total de chamados no período: <strong>{{ $total_chamados }}</strong>
    </div>

    <!-- ===== RESULTADOS DO PERÍODO ===== -->
    <div class="section-title">📋 Resumo do Período</div><br>
    <div class="row">
        <div class="stat-box">
            <strong>{{ $total_abertos }}</strong>
            <span class="label">Abertos</span>
        </div>
        <div class="stat-box">
            <strong>{{ $total_em_andamento }}</strong>
            <span class="label">Em Andamento</span>
        </div>
        <div class="stat-box">
            <strong>{{ $total_resolvidos }}</strong>
            <span class="label">Resolvidos</span>
        </div>
        <div class="stat-box">
            <strong>{{ $total_fechados }}</strong>
            <span class="label">Fechados</span>
        </div>
        <div class="stat-box">
            <strong>{{ $media_horas }}h</strong>
            <span class="label">Tempo Médio de Resolução</span>
        </div>
    </div>

    <!-- ===== CHAMADOS POR STATUS ===== -->
    <div class="section-title">📈 Chamados por Status</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Quantidade</th>
                <th>Percentual</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusMap = [
                    'aberto' => ['label' => 'Aberto', 'badge' => 'badge-primary'],
                    'em_andamento' => ['label' => 'Em Andamento', 'badge' => 'badge-warning'],
                    'resolvido' => ['label' => 'Resolvido', 'badge' => 'badge-success'],
                    'fechado' => ['label' => 'Fechado', 'badge' => 'badge-secondary'],
                ];
            @endphp
            @forelse($chamados_por_status as $item)
                @php
                    $statusInfo = $statusMap[$item->status] ?? ['label' => $item->status, 'badge' => 'badge-secondary'];
                    $percentual = $total_chamados > 0 ? round(($item->total / $total_chamados) * 100, 1) : 0;
                @endphp
                <tr>
                    <td><span class="badge {{ $statusInfo['badge'] }}">{{ $statusInfo['label'] }}</span></td>
                    <td>{{ $item->total }}</td>
                    <td>{{ $percentual }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nenhum dado disponível</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ===== CHAMADOS POR PRIORIDADE ===== -->
    <div class="section-title">🔴 Chamados por Prioridade</div>
    <table>
        <thead>
            <tr>
                <th>Prioridade</th>
                <th>Quantidade</th>
                <th>Percentual</th>
            </tr>
        </thead>
        <tbody>
            @php
                $priorityMap = [
                    'baixa' => ['label' => 'Baixa', 'badge' => 'badge-baixa'],
                    'normal' => ['label' => 'Normal', 'badge' => 'badge-normal'],
                    'alta' => ['label' => 'Alta', 'badge' => 'badge-alta'],
                    'urgente' => ['label' => 'Urgente', 'badge' => 'badge-urgente'],
                ];
            @endphp
            @forelse($chamados_por_prioridade as $item)
                @php
                    $priorityInfo = $priorityMap[$item->priority] ?? ['label' => $item->priority, 'badge' => 'badge-secondary'];
                    $percentual = $total_chamados > 0 ? round(($item->total / $total_chamados) * 100, 1) : 0;
                @endphp
                <tr>
                    <td><span class="badge {{ $priorityInfo['badge'] }}">{{ $priorityInfo['label'] }}</span></td>
                    <td>{{ $item->total }}</td>
                    <td>{{ $percentual }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nenhum dado disponível</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ===== CHAMADOS POR SETOR ===== -->
    <div class="section-title">🏢 Chamados por Setor</div>
    <table>
        <thead>
            <tr>
                <th>Setor</th>
                <th>Quantidade</th>
                <th>Percentual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($chamados_por_setor as $item)
                @php
                    $percentual = $total_chamados > 0 ? round(($item->total / $total_chamados) * 100, 1) : 0;
                @endphp
                <tr>
                    <td>{{ $item->sector }}</td>
                    <td>{{ $item->total }}</td>
                    <td>{{ $percentual }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nenhum dado disponível</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ===== TOP 10 USUÁRIOS ===== -->
    <div class="section-title">👤 Top 10 Usuários que mais abriram chamados</div>
    <table>
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Quantidade</th>
                <th>Percentual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($chamados_por_usuario as $item)
                @php
                    $percentual = $total_chamados > 0 ? round(($item->total / $total_chamados) * 100, 1) : 0;
                @endphp
                <tr>
                    <td>{{ $item->user }}</td>
                    <td>{{ $item->total }}</td>
                    <td>{{ $percentual }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nenhum dado disponível</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Gerado pelo sistema VitDesk em {{ $gerado_em }}
    </div>
</body>
</html>