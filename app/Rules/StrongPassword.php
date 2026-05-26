<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    public function passes($attribute, $value)
    {
        // Pelo menos 8 caracteres
        // Pelo menos 1 letra maiúscula
        // Pelo menos 1 caractere especial
        return preg_match('/^(?=.*[A-Z])(?=.*[!@#$%&*?])[A-Za-z\d!@#$%&*?]{8,}$/', $value);
    }

    public function message()
    {
        return 'A senha deve ter no mínimo 8 caracteres, 1 letra maiúscula e 1 caractere especial (! @ # $ % & * ?)';
    }
}