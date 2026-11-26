<?php
namespace App\Security\Domain\Model;

use App\Shared\Application\Security\SecurityRoles;

enum UserRole: string
{
    case USER = SecurityRoles::USER;
    case ADMIN = SecurityRoles::ADMIN;
    case MANAGER = SecurityRoles::MANAGER;
    case SUPER_ADMIN = SecurityRoles::SUPER_ADMIN;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
