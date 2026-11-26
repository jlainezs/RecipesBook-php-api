<?php
namespace App\Security\Domain\Model;

use App\Shared\Application\Security\SecurityRole;

enum UserRole: string
{
    case USER = SecurityRole::USER;
    case ADMIN = SecurityRole::ADMIN;
    case MANAGER = SecurityRole::MANAGER;
    case SUPER_ADMIN = SecurityRole::SUPER_ADMIN;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
