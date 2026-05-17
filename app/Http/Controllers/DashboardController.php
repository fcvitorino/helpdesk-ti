<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Sector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // USUÁRIO COMUM - mostra apenas seus próprios chamados
        if (!$user->isAdmin() && !$user->isTechnician()) {
            
            $totalTickets = Ticket::where('user_id', $user->id)->count();
            $openTickets = Ticket::where('user_id', $user->id)->where('status', 'aberto')->count();
            $inProgressTickets = Ticket::where('user_id', $user->id)->where('status', 'em_andamento')->count();
            $resolvedTickets = Ticket::where('user_id', $user->id)->where('status', 'resolvido')->count();
            
            // Chamados por prioridade (apenas os chamados do usuário)
            $ticketsByPriority = Ticket::where('user_id', $user->id)
                ->select('priority', DB::raw('count(*) as total'))
                ->groupBy('priority')
                ->get();
            
            // Chamados por mês (apenas os chamados do usuário)
            $ticketsByMonth = Ticket::where('user_id', $user->id)
                ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'))
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
                ->orderBy('month', 'asc')
                ->get();
            
            // Usuário comum NÃO vê gráficos por setor e local
            $ticketsBySector = collect();
            $ticketsByLocation = collect();
            
        } else {
            // ADMIN OU TÉCNICO - mostra dados da empresa selecionada (completo)
            
            if ($user->isAdmin() && session()->has('selected_company_id')) {
                $companyId = session('selected_company_id');
            } else {
                $companyId = $user->company_id ?? 1;
            }
            
            $totalTickets = Ticket::where('company_id', $companyId)->count();
            $openTickets = Ticket::where('company_id', $companyId)->where('status', 'aberto')->count();
            $inProgressTickets = Ticket::where('company_id', $companyId)->where('status', 'em_andamento')->count();
            $resolvedTickets = Ticket::where('company_id', $companyId)->where('status', 'resolvido')->count();
            
            // Chamados por setor
            $ticketsBySector = Sector::select('sectors.name', DB::raw('count(tickets.id) as total'))
                ->leftJoin('tickets', function($join) use ($companyId) {
                    $join->on('sectors.id', '=', 'tickets.sector_id')
                         ->where('tickets.company_id', '=', $companyId);
                })
                ->where('sectors.company_id', $companyId)
                ->groupBy('sectors.id', 'sectors.name')
                ->get();
            
            // Chamados por prioridade
            $ticketsByPriority = Ticket::where('company_id', $companyId)
                ->select('priority', DB::raw('count(*) as total'))
                ->groupBy('priority')
                ->get();
            
            // Chamados por local
            $ticketsByLocation = Ticket::where('company_id', $companyId)
                ->select('location', DB::raw('count(*) as total'))
                ->groupBy('location')
                ->get();
            
            // Chamados por mês
            $ticketsByMonth = Ticket::where('company_id', $companyId)
                ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'))
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
                ->orderBy('month', 'asc')
                ->get();
        }
        
        return view('dashboard', compact(
            'totalTickets', 'openTickets', 'inProgressTickets', 'resolvedTickets',
            'ticketsBySector', 'ticketsByPriority', 'ticketsByLocation', 'ticketsByMonth'
        ));
    }
}