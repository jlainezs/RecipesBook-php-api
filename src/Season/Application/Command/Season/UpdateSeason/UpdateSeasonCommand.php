<?php
namespace App\Season\Application\Command\Season\UpdateSeason;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
final readonly class UpdateSeasonCommand
{
    public function __construct(
        public string $id,
        public string $name
    ){}
}
