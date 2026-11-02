<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure;

use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;

class UpdateUnitOfMeasureCommandFactory
{
    public function createFromRequest(UpdateUnitOfMeasureRouteDto $routeDto, UpdateUnitOfMeasureDto $dto): UpdateUnitOfMeasureCommand
    {
        return new UpdateUnitOfMeasureCommand(
            $routeDto->id,
            $dto->name,
            $dto->symbol,
            null !== $dto->unitOfMeasureType ? UnitOfMeasureEnum::from($dto->unitOfMeasureType) : null
        );
    }
}
