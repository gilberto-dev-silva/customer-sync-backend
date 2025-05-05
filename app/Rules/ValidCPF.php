<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCPF implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/[^0-9]/', '', $value);

        if (strlen($cpf) != 11) {
            $fail('O CPF deve conter 11 dígitos.');
            return;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $fail('CPF inválido (não pode ter todos os dígitos iguais).');
            return;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }

        $rest = $sum % 11;
        $digit1 = ($rest < 2) ? 0 : 11 - $rest;

        if ($cpf[9] != $digit1) {
            $fail('CPF inválido (primeiro dígito verificador incorreto).');
            return;
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }

        $rest = $sum % 11;
        $digit2 = ($rest < 2) ? 0 : 11 - $rest;

        if ($cpf[10] != $digit2) {
            $fail('CPF inválido (segundo dígito verificador incorreto).');
            return;
        }
    }

    /**
     * Formata o CPF para exibição
     */
    public static function format(string $cpf): string
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    /**
     * Remove a formatação do CPF
     */
    public static function clean(string $cpf): string
    {
        return preg_replace('/[^0-9]/', '', $cpf);
    }
}
