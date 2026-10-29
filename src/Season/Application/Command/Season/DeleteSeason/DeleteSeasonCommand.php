<?php
namespace App\Season\Application\Command\Season\DeleteSeason;

readonly final class DeleteSeasonCommand
{
    public function __construct(
        public string $id
    ){}
}
