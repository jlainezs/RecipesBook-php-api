<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure;


readonly final class UnitOfMeasureDeleteCommand
{
    public function __construct(public readonly string $id)
    {}
}
