<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'email',
        'token',
        'role',
        'company_id',
        'sector_id',
        'status',
        'expires_at',
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
    
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
    
    public function isPending()
    {
        return $this->status === 'pending';
    }
}