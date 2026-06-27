<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure;

final readonly class UnitOfMeasureInstanceResponse
{
    public function __construct(public readonly ?UnitOfMeasureDto $unitOfMeasure)
    {}
}
