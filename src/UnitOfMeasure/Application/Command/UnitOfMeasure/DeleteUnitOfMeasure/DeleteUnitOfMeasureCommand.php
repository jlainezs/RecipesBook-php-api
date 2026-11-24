<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly final class DeleteUnitOfMeasureCommand
{
    public function __construct(
        public string $id
    ){}
}
