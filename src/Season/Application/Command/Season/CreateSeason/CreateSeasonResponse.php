<?php
namespace App\Season\Application\Command\Season\CreateSeason;

class CreateSeasonResponse
{
    public function __construct(
        public string $id
    ){}
}
