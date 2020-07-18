<?php

declare(strict_types=1);

namespace SimplePhpCrud\Validation;

/**
 * Class ClienteValidation
 */
class ClienteValidation
{
    /**
     * @param string $nomeCompleto
     *
     * @return bool
     */
    public static function isValidNomeCompleto(string $nomeCompleto): bool
    {
        $lenNome = strlen($nomeCompleto);

        return $lenNome >= 3 && $lenNome <= 150;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 150;
    }

    /**
     * @param string $cpf
     *
     * @return bool
     */
    public static function isValidCpf(string $cpf): bool
    {
        if (!isset($cpf) || strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $telefone
     *
     * @return bool
     */
    public static function isValidTelefone(?string $telefone): bool
    {
        if (empty($telefone)) {
            return true;
        }

        return is_numeric($telefone) && strlen($telefone) === 11;
    }
}
