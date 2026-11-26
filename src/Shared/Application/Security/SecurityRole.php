<?php
namespace App\Shared\Application\Security;
final readonly class SecurityRole
{
    public const string USER = 'ROLE_USER';
    public const string ADMIN = 'ROLE_ADMIN';
    public const string MANAGER = 'ROLE_MANAGER';
    public const string SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public function __construct()
    {}
}
