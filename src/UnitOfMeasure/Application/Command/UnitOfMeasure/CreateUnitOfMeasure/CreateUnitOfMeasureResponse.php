<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure;

final readonly class CreateUnitOfMeasureResponse
{
    public function __construct(
        public string $id
    ){}
}
