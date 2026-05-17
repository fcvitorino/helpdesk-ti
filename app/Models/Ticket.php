<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'assigned_to',
        'sector_id',
        'title',
        'description',
        'location',
        'priority',
        'status',
        'resolved_at',
        'ticket_number'
    ];

    protected $casts = [
        'resolved_at' => 'datetime'
    ];

    // Auto gerar número do chamado
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticket) {
            $ticket->ticket_number = self::generateTicketNumber();
        });
    }

    public static function generateTicketNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastTicket = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastTicket && $lastTicket->ticket_number) {
            $lastNumber = (int) $lastTicket->ticket_number;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "{$year}{$month}{$newNumber}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
}