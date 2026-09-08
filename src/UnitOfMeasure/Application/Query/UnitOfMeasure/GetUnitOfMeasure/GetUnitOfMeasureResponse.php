<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure;

use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureDto;

final readonly class GetUnitOfMeasureResponse
{
    public function __construct(public ?UnitOfMeasureDto $unitOfMeasure)
    {}
}
