<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure;

use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUnitOfMeasureCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\NotBlank]
        public string $name,

        #[Assert\NotBlank]
        public string $symbol,
        public UnitOfMeasureEnum $unitOfMeasureEnum
    ){}
}
