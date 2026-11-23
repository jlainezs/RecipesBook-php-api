<?php
namespace App\Shared\Application\Security;

use App\Security\Domain\Model\UserRole;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class IsAuthorized
{
    public array $roles;

    public function __construct(UserRole|string ...$roles)
    {
        $this->roles = $roles;
    }
}
