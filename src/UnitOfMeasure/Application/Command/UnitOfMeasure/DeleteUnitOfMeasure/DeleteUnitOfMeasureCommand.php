<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure;

readonly final class DeleteUnitOfMeasureCommand
{
    public function __construct(
        public string $id
    ){}
}
