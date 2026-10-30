<?php
namespace App\Season\Application\Command\Season\UpdateSeason;

class UpdateSeasonCommandFactory
{
    public function createFromRequest(UpdateSeasonRouteDto $routeDto, UpdateSeasonDto $dto): UpdateSeasonCommand
    {
        return new UpdateSeasonCommand($routeDto->id, $dto->name);
    }
}
