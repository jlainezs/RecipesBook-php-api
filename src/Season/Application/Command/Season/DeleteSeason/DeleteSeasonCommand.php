<?php
namespace App\Season\Application\Command\Season\DeleteSeason;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly final class DeleteSeasonCommand
{
    public function __construct(
        public string $id
    ){}
}
