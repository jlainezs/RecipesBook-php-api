<?php

namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure;

use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;

class CreateUnitOfMeasureDto
{
    public function __construct(
        public string $name,
        public string $symbol,
        public int $unitOfMeasureType
    ) {}
}
