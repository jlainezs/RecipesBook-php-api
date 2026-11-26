<?php
namespace App\Shared\Application\Security;

use Attribute;
use BackedEnum;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class IsAuthorized
{
    public array $roles;

    public function __construct(BackedEnum|string ...$roles)
    {
        $this->roles = $roles;
    }
}
