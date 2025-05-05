<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCNPJ implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cnpj = preg_replace('/[^0-9]/', '', $value);

        if (strlen($cnpj) != 14) {
            $fail('O CNPJ deve conter 14 dígitos.');
            return;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            $fail('CNPJ inválido (não pode ter todos os dígitos iguais).');
            return;
        }

        $sum = 0;
        $weight = 5;
        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $weight;
            $weight = ($weight == 2) ? 9 : $weight - 1;
        }

        $rest = $sum % 11;
        $digit1 = ($rest < 2) ? 0 : 11 - $rest;

        if ($cnpj[12] != $digit1) {
            $fail('CNPJ inválido (dígito verificador incorreto).');
            return;
        }

        $sum = 0;
        $weight = 6;
        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $weight;
            $weight = ($weight == 2) ? 9 : $weight - 1;
        }

        $rest = $sum % 11;
        $digit2 = ($rest < 2) ? 0 : 11 - $rest;

        if ($cnpj[13] != $digit2) {
            $fail('CNPJ inválido (dígito verificador incorreto).');
            return;
        }
    }

    public static function format(string $cnpj): string
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
    }
}
