<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;


class AuditController extends Controller
{
    public function index(Request $request)
    {
        // Verifica se é admin
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Obtém a empresa selecionada
        $empresaId = session('selected_company_id', Auth::user()->company_id);

        // Query base - FILTRA PELA EMPRESA
        $query = ActivityLog::with(['causer', 'subject'])
            ->whereIn('log_name', ['auditoria', 'default'])
            ->whereHas('subject', function ($q) use ($empresaId) {
                $q->where('company_id', $empresaId);
            })
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('ticket_id')) {
            $query->where('subject_id', $request->ticket_id)
                  ->where('subject_type', Ticket::class);
        }

        if ($request->filled('sector_id')) {
            $ticketsIds = Ticket::where('sector_id', $request->sector_id)->pluck('id');
            $query->where('subject_type', Ticket::class)
                  ->whereIn('subject_id', $ticketsIds);
        }

        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        if ($request->filled('acao')) {
            $query->where('description', 'like', '%' . $request->acao . '%');
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        // Paginação
        $logs = $query->paginate(20)->withQueryString();

        // Dados para os selects de filtro (também filtrados pela empresa)
        $sectors = Sector::where('company_id', $empresaId)->orderBy('name')->pluck('name', 'id');
        $users = User::where('company_id', $empresaId)->orderBy('name')->pluck('name', 'id');
        $tickets = Ticket::where('company_id', $empresaId)
            ->orderBy('id', 'desc')
            ->limit(100)
            ->pluck('ticket_number', 'id');

        return view('admin.audit.index', compact('logs', 'sectors', 'users', 'tickets'));
    }

    public function exportPdf(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $empresaId = session('selected_company_id', Auth::user()->company_id);

        $query = ActivityLog::with(['causer', 'subject'])
            ->whereIn('log_name', ['auditoria', 'default'])
            ->whereHas('subject', function ($q) use ($empresaId) {
                $q->where('company_id', $empresaId);
            })
            ->orderBy('created_at', 'desc');

        if ($request->filled('ticket_id')) {
            $query->where('subject_id', $request->ticket_id)
                  ->where('subject_type', Ticket::class);
        }

        if ($request->filled('sector_id')) {
            $ticketsIds = Ticket::where('sector_id', $request->sector_id)->pluck('id');
            $query->where('subject_type', Ticket::class)
                  ->whereIn('subject_id', $ticketsIds);
        }

        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        if ($request->filled('acao')) {
            $query->where('description', 'like', '%' . $request->acao . '%');
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        $logs = $query->get();

        $pdf = Pdf::loadView('admin.audit.pdf', compact('logs'));
        return $pdf->download('auditoria_' . date('Y-m-d_Hi') . '.pdf');
    }
}