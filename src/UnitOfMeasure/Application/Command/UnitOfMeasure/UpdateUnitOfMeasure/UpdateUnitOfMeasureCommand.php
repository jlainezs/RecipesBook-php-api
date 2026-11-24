<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure;

use App\Shared\Application\Security\IsPublicOperation;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;

#[IsPublicOperation]
final readonly class UpdateUnitOfMeasureCommand
{
    public function __construct(
        public string $id,
        public ?string $name,
        public ?string $symbol,
        public ?UnitOfMeasureEnum $unitOfMeasureEnum
    ){}
}
