<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class UnitOfMeasureDeleteCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
