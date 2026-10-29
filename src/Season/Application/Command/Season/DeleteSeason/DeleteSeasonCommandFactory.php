<?php
namespace App\Season\Application\Command\Season\DeleteSeason;

class DeleteSeasonCommandFactory
{
    public function createFromDto(DeleteSeasonDto $dto): DeleteSeasonCommand
    {
        return new DeleteSeasonCommand($dto->id);
    }
}
