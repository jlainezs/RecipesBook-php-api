<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures;

final readonly class GetUnitsOfMeasureDto
{
    public function __construct(
        public int $offset = 0,
        public int $limit = 20,
    ){}
}
