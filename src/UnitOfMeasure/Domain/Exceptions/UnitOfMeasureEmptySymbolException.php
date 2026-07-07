<?php

namespace App\UnitOfMeasure\Domain\Exceptions;

use InvalidArgumentException;

class UnitOfMeasureEmptySymbolException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Unit of measure symbol cannot be empty', 0);
    }
}
