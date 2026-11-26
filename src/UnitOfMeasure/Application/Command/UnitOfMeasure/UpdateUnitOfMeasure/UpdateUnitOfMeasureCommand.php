<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;

#[IsAuthorized(SecurityRole::USER)]
final readonly class UpdateUnitOfMeasureCommand
{
    public function __construct(
        public string $id,
        public ?string $name,
        public ?string $symbol,
        public ?UnitOfMeasureEnum $unitOfMeasureEnum
    ){}
}
