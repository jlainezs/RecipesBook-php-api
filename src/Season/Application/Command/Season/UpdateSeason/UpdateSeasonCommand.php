<?php
namespace App\Season\Application\Command\Season\UpdateSeason;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class UpdateSeasonCommand
{
    public function __construct(
        public string $id,
        public string $name
    ){}
}
