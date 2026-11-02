<?php

namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUnitOfMeasureDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public string $symbol,
        #[Assert\GreaterThanOrEqual(0)]
        #[Assert\LessThanOrEqual(2)]
        public int $unitOfMeasureType
    ) {}
}
