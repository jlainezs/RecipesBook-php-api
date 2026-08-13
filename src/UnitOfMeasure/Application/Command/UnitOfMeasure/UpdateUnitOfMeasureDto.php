<?php

namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure;

use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;

class UpdateUnitOfMeasureDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $symbol,
        public int $unitOfMeasureType
    ) {}
}
