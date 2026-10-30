<?php
namespace App\Season\Application\Command\Season\UpdateSeason;

final readonly class UpdateSeasonCommand
{
    public function __construct(
        public string $id,
        public string $name
    ){}
}
