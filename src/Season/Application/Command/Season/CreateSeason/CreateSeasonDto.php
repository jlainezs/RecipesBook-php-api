<?php
namespace App\Season\Application\Command\Season\CreateSeason;

final readonly class CreateSeasonDto
{
    public function __construct(
        public string $name,
    ) {}
}
