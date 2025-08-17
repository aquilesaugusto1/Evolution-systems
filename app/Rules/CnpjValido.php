<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnpjValido implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! $this->isCnpj($value)) {
            $fail('O campo :attribute não é um CNPJ válido.');
        }
    }

    /**
     * Valida um número de CNPJ.
     */
    private function isCnpj(string $cnpj): bool
    {
        if (empty($cnpj)) {
            return false;
        }

        // Remove caracteres não numéricos
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if ($cnpj === null) {
            return false;
        }

        // Verifica se o CNPJ tem 14 dígitos
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Verifica se todos os dígitos são iguais (ex: 11.111.111/1111-11)
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Valida o primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += (int) $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ((int) $cnpj[12] !== ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        // Valida o segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += (int) $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return (int) $cnpj[13] === ($resto < 2 ? 0 : 11 - $resto);
    }
}