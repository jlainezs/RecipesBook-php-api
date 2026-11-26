<?php
namespace App\Season\Application\Command\Season\DeleteSeason;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly final class DeleteSeasonCommand
{
    public function __construct(
        public string $id
    ){}
}
