<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id',
        'sector_id',
        'email_verified_at',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // Métodos de permissão
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    public function isTechnician()
    {
        return $this->role === 'technician';
    }
    
    // Relacionamentos
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }
    
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'technician_id');
    }
    
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}