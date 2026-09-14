<?php
namespace App\Security\Domain\Model;

enum UserRole: string
{
    case USER = "ROLE_USER";
    case ADMIN = "ROLE_ADMIN";
    case MANAGER = "ROLE_MANAGER";
    case SUPER_ADMIN = "ROLE_SUPER_ADMIN";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
