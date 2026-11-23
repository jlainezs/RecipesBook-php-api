<?php
namespace App\Shared\Application\Security;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class IsPublicOperation
{}
