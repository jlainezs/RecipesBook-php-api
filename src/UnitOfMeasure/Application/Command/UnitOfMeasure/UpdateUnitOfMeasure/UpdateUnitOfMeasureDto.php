<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Partial update: null means "keep the current value".
 */
class UpdateUnitOfMeasureDto
{
    public function __construct(
        #[Assert\NotBlank(allowNull: true)]
        public ?string $name = null,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $symbol = null,
        #[Assert\GreaterThanOrEqual(0)]
        #[Assert\LessThanOrEqual(2)]
        public ?int $unitOfMeasureType = null
    ) {}
}
