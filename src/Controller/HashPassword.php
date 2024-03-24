<?php

namespace App\Security;

class PasswordHashing
{
    public static function hashPassword(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    public static function verifyPassword(string $plainPassword, string $hashedPassword): bool
    {
        return password_verify($plainPassword, $hashedPassword);
    }
}