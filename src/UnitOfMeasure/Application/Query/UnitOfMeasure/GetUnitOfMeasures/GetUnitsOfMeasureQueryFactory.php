<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures;

class GetUnitsOfMeasureQueryFactory
{
    public function createFromDto(GetUnitsOfMeasureDto $dto): GetUnitsOfMeasureQuery
    {
        return new GetUnitsOfMeasureQuery($dto->offset, $dto->limit);
    }
}
