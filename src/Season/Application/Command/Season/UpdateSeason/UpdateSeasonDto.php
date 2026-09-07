<?php
namespace App\Season\Application\Command\Season\UpdateSeason;

final readonly class UpdateSeasonDto
{
    public function __construct(
        public string $name
    ){}
}
