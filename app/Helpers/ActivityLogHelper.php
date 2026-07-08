<?php

namespace App\Helpers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class ActivityLogHelper
{
    public static function log($ticket, $description, $properties = [])
    {
        if (!$ticket instanceof Ticket) {
            $ticket = Ticket::findOrFail($ticket);
        }

        activity('auditoria')
            ->performedOn($ticket)
            ->causedBy(Auth::user())
            ->withProperties($properties)
            ->log($description);
    }

    public static function logComment($ticket, $comment)
    {
        self::log($ticket, 'Comentário adicionado', [
            'comentario' => $comment,
            'tipo' => 'comentario'
        ]);
    }

    public static function logAttachment($ticket, $file)
    {
        self::log($ticket, 'Anexo adicionado', [
            'arquivo' => $file,
            'tipo' => 'anexo'
        ]);
    }

    public static function logStatusChange($ticket, $oldStatus, $newStatus)
    {
        self::log($ticket, 'Status alterado', [
            'status_anterior' => $oldStatus,
            'status_novo' => $newStatus,
            'tipo' => 'status'
        ]);
    }
}