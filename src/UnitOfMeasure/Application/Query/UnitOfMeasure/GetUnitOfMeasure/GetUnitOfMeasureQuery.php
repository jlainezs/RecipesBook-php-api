<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class GetUnitOfMeasureQuery
{
    public function __construct(public string $id)
    {}
}
