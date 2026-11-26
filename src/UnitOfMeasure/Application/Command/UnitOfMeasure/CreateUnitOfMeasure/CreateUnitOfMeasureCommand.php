<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use Symfony\Component\Validator\Constraints as Assert;

#[IsAuthorized(SecurityRole::USER)]
readonly final class CreateUnitOfMeasureCommand
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
