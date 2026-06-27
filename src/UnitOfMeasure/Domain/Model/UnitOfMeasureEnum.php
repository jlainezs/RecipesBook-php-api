<?php
namespace App\UnitOfMeasure\Domain\Model;

enum UnitOfMeasureEnum: int
{
    case Weight = 0;
    case Volume = 1;
    case Units = 2;
}
