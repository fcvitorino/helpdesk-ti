<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'cnpj',
        'telefone',
        'endereco',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function sectors()
    {
        return $this->hasMany(Sector::class);
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}