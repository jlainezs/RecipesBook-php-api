<?php

namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteUnitOfMeasureRouteDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
