<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure;

readonly final class UnitsOfMeasureQueryResponse
{
    public function __construct(
        /**
         * @var UnitOfMeasureDto[]
         */
        public readonly array $items,
    ){}
}
