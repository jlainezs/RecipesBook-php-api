<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures;

use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureDto;

readonly final class GetUnitsOfMeasureQueryResponse
{
    public function __construct(
        /**
         * @var UnitOfMeasureDto[]
         */
        public array $items,
    ){}
}
