<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class DeleteUnitOfMeasureCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
