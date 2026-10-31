<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure;

use Symfony\Component\Validator\Constraints as Assert;
class UpdateUnitOfMeasureDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public string $symbol,
        #[Assert\Positive()]
        public int $unitOfMeasureType
    ) {}
}
