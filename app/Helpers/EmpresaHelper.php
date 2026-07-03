<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class EmpresaHelper
{
    public static function getId()
    {
        // Primeiro tenta da sessão (chave usada pelo CompanySwitchController)
        $empresaId = Session::get('selected_company_id');

        // Se não tiver, tenta a chave antiga (compatibilidade)
        if (!$empresaId) {
            $empresaId = Session::get('empresa_id');
        }
        if (!$empresaId) {
            $empresaId = Session::get('company_id');
        }

        if ($empresaId) {
            return $empresaId;
        }

        // Se não tiver na sessão, tenta do usuário logado
        if (auth()->check() && auth()->user()->company_id) {
            return auth()->user()->company_id;
        }

        return null;
    }

    public static function setEmpresa($empresaId)
    {
        // Define em todas as chaves possíveis para compatibilidade
        Session::put('selected_company_id', $empresaId);
        Session::put('empresa_id', $empresaId);
        Session::put('company_id', $empresaId);
    }
}