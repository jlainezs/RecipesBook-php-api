<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure;

class DeleteUnitOfMeasureCommandFactory
{
    public function createFromRequest(DeleteUnitOfMeasureRouteDto $dto): DeleteUnitOfMeasureCommand
    {
        return new DeleteUnitOfMeasureCommand($dto->id);
    }
}
