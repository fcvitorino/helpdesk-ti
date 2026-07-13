<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Sector;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $companyId = session('selected_company_id', Auth::user()->company_id);

        $sectors = Sector::where('company_id', $companyId)->orderBy('name')->pluck('name', 'id');

        return view('admin.reports.index', compact('sectors'));
    }

    public function generate(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $companyId = session('selected_company_id', Auth::user()->company_id);
        $dataInicio = $request->data_inicio . ' 00:00:00';
        $dataFim = $request->data_fim . ' 23:59:59';

        // 1. Total de chamados no período
        $totalChamados = Ticket::where('company_id', $companyId)
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->count();

        // 2. Chamados por status
        $chamadosPorStatus = Ticket::where('company_id', $companyId)
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // 3. Chamados por prioridade
        $chamadosPorPrioridade = Ticket::where('company_id', $companyId)
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->get();

        // 4. Chamados por setor (CORRIGIDO)
        $chamadosPorSetor = Ticket::where('tickets.company_id', $companyId)
            ->whereBetween('tickets.created_at', [$dataInicio, $dataFim])
            ->join('sectors', 'tickets.sector_id', '=', 'sectors.id')
            ->select('sectors.name as sector', DB::raw('count(*) as total'))
            ->groupBy('sectors.name')
            ->get();

        // 5. Chamados por usuário (CORRIGIDO)
        $chamadosPorUsuario = Ticket::where('tickets.company_id', $companyId)
            ->whereBetween('tickets.created_at', [$dataInicio, $dataFim])
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->select('users.name as user', DB::raw('count(*) as total'))
            ->groupBy('users.name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // 6. Tempo médio de resolução
        $tempoMedioResolucao = Ticket::where('company_id', $companyId)
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->whereNotNull('resolved_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as media_horas'))
            ->first();

        $mediaHoras = $tempoMedioResolucao->media_horas ?? 0;

        // 7-10. Totais por status
        $totalResolvidos = Ticket::where('company_id', $companyId)
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->where('status', 'resolvido')
            ->count();

        $totalFechados = Ticket::where('company_id', $companyId)
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->where('status', 'fechado')
            ->count();

        $totalEmAndamento = Ticket::where('company_id', $companyId)
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->where('status', 'em_andamento')
            ->count();

        $totalAbertos = Ticket::where('company_id', $companyId)
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->where('status', 'aberto')
            ->count();

        $dados = [
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'total_chamados' => $totalChamados,
            'chamados_por_status' => $chamadosPorStatus,
            'chamados_por_prioridade' => $chamadosPorPrioridade,
            'chamados_por_setor' => $chamadosPorSetor,
            'chamados_por_usuario' => $chamadosPorUsuario,
            'media_horas' => round($mediaHoras, 1),
            'total_resolvidos' => $totalResolvidos,
            'total_fechados' => $totalFechados,
            'total_em_andamento' => $totalEmAndamento,
            'total_abertos' => $totalAbertos,
            'empresa' => Company::find($companyId),
            'gerado_em' => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', $dados);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('relatorio_chamados_' . date('Y-m-d') . '.pdf');
    }
}