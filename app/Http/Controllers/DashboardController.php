<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $companyId = session('selected_company_id', $user->company_id);
        
        // 🔒 REGRA DE VISIBILIDADE DO DASHBOARD:
        // - Admin: vê dados de TODOS os chamados da empresa selecionada (com setor)
        // - Técnico: vê dados de TODOS os chamados da empresa selecionada (com setor)
        // - Usuário comum: vê dados APENAS dos chamados que ele mesmo abriu (sem setor)
        
        if ($user->isAdmin() || $user->isTechnician()) {
            // ========== ADMIN OU TÉCNICO ==========
            $totalTickets = Ticket::where('company_id', $companyId)->count();
            $ticketsAberto = Ticket::where('company_id', $companyId)->where('status', 'aberto')->count();
            $ticketsEmAndamento = Ticket::where('company_id', $companyId)->where('status', 'em_andamento')->count();
            $ticketsResolvido = Ticket::where('company_id', $companyId)->where('status', 'resolvido')->count();
            
            // Chamados por setor (todos da empresa)
            $ticketsPorSetor = Sector::where('sectors.company_id', $companyId)
                ->leftJoin('tickets', 'sectors.id', '=', 'tickets.sector_id')
                ->select('sectors.name', DB::raw('count(tickets.id) as total'))
                ->groupBy('sectors.id', 'sectors.name')
                ->get();
            
            // Chamados por prioridade (todos da empresa)
            $ticketsPorPrioridade = Ticket::where('company_id', $companyId)
                ->select('priority', DB::raw('count(*) as total'))
                ->groupBy('priority')
                ->get();
            
            // Chamados por local (todos da empresa)
            $ticketsPorLocal = Ticket::where('company_id', $companyId)
                ->select('location', DB::raw('count(*) as total'))
                ->groupBy('location')
                ->get();
            
            // Chamados por mês (todos da empresa)
            $ticketsPorMes = Ticket::where('company_id', $companyId)
                ->where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('count(*) as total'))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();
                
            // Flag para saber se deve mostrar gráfico de setor
            $showSectorChart = true;
            
        } else {
            // ========== USUÁRIO COMUM ==========
            $totalTickets = Ticket::where('user_id', $user->id)->count();
            $ticketsAberto = Ticket::where('user_id', $user->id)->where('status', 'aberto')->count();
            $ticketsEmAndamento = Ticket::where('user_id', $user->id)->where('status', 'em_andamento')->count();
            $ticketsResolvido = Ticket::where('user_id', $user->id)->where('status', 'resolvido')->count();
            
            // Usuário comum NÃO vê gráfico de setor (array vazio)
            $ticketsPorSetor = collect([]);
            
            // Chamados por prioridade (apenas do usuário)
            $ticketsPorPrioridade = Ticket::where('user_id', $user->id)
                ->select('priority', DB::raw('count(*) as total'))
                ->groupBy('priority')
                ->get();
            
            // Chamados por local (apenas do usuário)
            $ticketsPorLocal = Ticket::where('user_id', $user->id)
                ->select('location', DB::raw('count(*) as total'))
                ->groupBy('location')
                ->get();
            
            // Chamados por mês (apenas do usuário)
            $ticketsPorMes = Ticket::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('count(*) as total'))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();
                
            // Flag para saber se deve mostrar gráfico de setor
            $showSectorChart = false;
        }
        
        return view('dashboard', compact(
            'totalTickets',
            'ticketsAberto',
            'ticketsEmAndamento',
            'ticketsResolvido',
            'ticketsPorSetor',
            'ticketsPorPrioridade',
            'ticketsPorLocal',
            'ticketsPorMes',
            'showSectorChart'
        ));
    }
}