<?php

declare(strict_types=1);

namespace SimplePhpCrud\Validation;

/**
 * Class ClientValidation
 */
class ClientValidation
{
    /**
     * @param string $fullName
     *
     * @return bool
     */
    public static function isValidFullName(string $fullName): bool
    {
        $pregResult = preg_match('/^[A-ZÀ-Ÿ][A-zÀ-ÿ\']+\s([A-zÀ-ÿ\']\s?)*[A-ZÀ-Ÿ][A-zÀ-ÿ\']+$/', $fullName);
        $lenNome = strlen($fullName);

        return $lenNome >= 3 && $lenNome <= 150 && $pregResult;
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
     * @param string $phone
     *
     * @return bool
     */
    public static function isValidPhone(?string $phone): bool
    {
        if (empty($phone)) {
            return true;
        }

        return is_numeric($phone) && strlen($phone) === 11;
    }
}
