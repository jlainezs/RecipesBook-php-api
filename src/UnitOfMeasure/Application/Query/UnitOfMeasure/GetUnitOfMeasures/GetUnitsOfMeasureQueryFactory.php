<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures;

class GetUnitsOfMeasureQueryFactory
{
    public function createFromDto(GetUnitsOfMeasureDto $dto): GetUnitsOfMeasureQuery
    {
        return new GetUnitsOfMeasureQuery($dto->page->offset, $dto->page->limit);
    }
}
