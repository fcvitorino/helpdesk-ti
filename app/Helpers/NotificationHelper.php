<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;

class NotificationHelper
{
    public static function send($userId, $ticketId, $type, $title, $message, $sendEmail = true)
    {
        // Salvar no banco
        Notification::create([
            'user_id' => $userId,
            'ticket_id' => $ticketId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);
        
        // Enviar email
        if ($sendEmail) {
            $user = User::find($userId);
            $ticket = Ticket::find($ticketId);
            
            if ($user && $ticket) {
                try {
                    // Enviar email com HTML formatado
                    Mail::send('emails.notification', [
                        'user' => $user,
                        'ticket' => $ticket,
                        'title' => $title,
                        'message' => $message,
                    ], function ($mail) use ($user, $title) {
                        $mail->to($user->email);
                        $mail->subject('HelpDesk TI - ' . $title);
                    });
                    
                } catch (\Exception $e) {
                    // Silencia erro para não travar o sistema
                }
            }
        }
    }
    
    public static function notifyNewTicket($ticket)
    {
        // Notificar ADMIN e TÉCNICOS
        $users = User::where('company_id', $ticket->company_id)
            ->where(function($q) {
                $q->where('role', 'admin')->orWhere('role', 'technician');
            })
            ->get();
        
        foreach ($users as $user) {
            self::send(
                $user->id,
                $ticket->id,
                'new_ticket',
                '📢 Novo Chamado #' . $ticket->ticket_number,
                "Chamado aberto por {$ticket->user->name}: {$ticket->title}",
                true
            );
        }
        
        // Notificar o próprio usuário
        self::send(
            $ticket->user_id,
            $ticket->id,
            'new_ticket',
            '✅ Chamado Aberto #' . $ticket->ticket_number,
            "Seu chamado foi aberto. Aguarde o atendimento.",
            true
        );
    }
    
    public static function notifyNewComment($ticket, $comment, $author)
    {
        $users = User::where('company_id', $ticket->company_id)
            ->where(function($q) {
                $q->where('role', 'admin')->orWhere('role', 'technician');
            })
            ->get();
        
        $userIds = $users->pluck('id')->toArray();
        if (!in_array($ticket->user_id, $userIds)) {
            $userIds[] = $ticket->user_id;
        }
        
        $userIds = array_diff($userIds, [$author->id]);
        
        foreach ($userIds as $userId) {
            self::send(
                $userId,
                $ticket->id,
                'new_comment',
                '💬 Novo Comentário #' . $ticket->ticket_number,
                "{$author->name} comentou: " . substr($comment->message, 0, 50),
                true
            );
        }
    }
    
    public static function notifyStatusChange($ticket, $oldStatus, $newStatus, $changedBy)
    {
        if ($ticket->user_id != $changedBy->id) {
            self::send(
                $ticket->user_id,
                $ticket->id,
                'status_change',
                '📌 Status Alterado #' . $ticket->ticket_number,
                "Status alterado de '{$oldStatus}' para '{$newStatus}' por {$changedBy->name}",
                true
            );
        }
        
        $users = User::where('company_id', $ticket->company_id)
            ->where(function($q) {
                $q->where('role', 'admin')->orWhere('role', 'technician');
            })
            ->where('id', '!=', $changedBy->id)
            ->get();
        
        foreach ($users as $user) {
            self::send(
                $user->id,
                $ticket->id,
                'status_change',
                '📌 Status Alterado #' . $ticket->ticket_number,
                "Status alterado de '{$oldStatus}' para '{$newStatus}' por {$changedBy->name}",
                true
            );
        }
    }
}