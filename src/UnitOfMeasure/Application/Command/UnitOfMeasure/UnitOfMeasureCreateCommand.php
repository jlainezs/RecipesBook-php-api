<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure;

use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;

readonly final class UnitOfMeasureCreateCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $symbol,
        public readonly UnitOfMeasureEnum $unitOfMeasureEnum
    ){}
}
