<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'location',
        'priority',
        'status',
        'company_id',
        'user_id',
        'sector_id',
        'technician_id',
        'resolved_at'
    ];
    
    protected $casts = [
        'resolved_at' => 'datetime',
    ];
    
    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
    
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
    
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
    
    // Scopes para filtros
    public function scopeOpen($query)
    {
        return $query->where('status', 'aberto');
    }
    
    public function scopeInProgress($query)
    {
        return $query->where('status', 'em_andamento');
    }
    
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolvido');
    }
    
    public function scopeClosed($query)
    {
        return $query->where('status', 'fechado');
    }
}