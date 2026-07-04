<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure;

use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use Symfony\Component\Validator\Constraints as Assert;

readonly final class UnitOfMeasureCreateCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,

        #[Assert\NotBlank]
        public string $symbol,
        public UnitOfMeasureEnum $unitOfMeasureEnum
    ){}
}
