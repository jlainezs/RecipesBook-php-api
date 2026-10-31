<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure;

class GetUnitOfMeasureQueryFactory
{
    public function createFromDto(GetUnitOfMeasureRouteQueryDto $query): GetUnitOfMeasureQuery
    {
        return new GetUnitOfMeasureQuery($query->id);
    }
}
