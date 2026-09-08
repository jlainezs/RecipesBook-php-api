<?php

namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure;

class UpdateUnitOfMeasureDto
{
    public function __construct(
        public string $name,
        public string $symbol,
        public int $unitOfMeasureType
    ) {}
}
