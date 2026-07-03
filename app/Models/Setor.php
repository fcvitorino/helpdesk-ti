<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'empresa_id'];

    /**
     * Relacionamento com empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Escopo para filtrar por uma empresa específica
     */
    public function scopeDaEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    /**
     * Escopo para filtrar pela empresa atual (da sessão)
     */
    public function scopeDaEmpresaAtual($query)
    {
        $empresaId = session('empresa_id') ?? auth()->user()->empresa_id ?? null;
        if ($empresaId) {
            return $query->where('empresa_id', $empresaId);
        }
        return $query;
    }
}