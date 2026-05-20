<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'is_support',
    ];
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // ⬇️ ESSE MÉTODO É FUNDAMENTAL PARA OS ANEXOS ⬇️
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'reply_id');
    }
}