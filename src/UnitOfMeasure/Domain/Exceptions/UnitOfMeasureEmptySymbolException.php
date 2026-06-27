<?php

namespace App\UnitOfMeasure\Domain\Exceptions;

use Exception;

class UnitOfMeasureEmptySymbolException extends Exception
{
    public function __construct()
    {
        parent::__construct('Unit of measure symbol cannot be empty', 0);
    }
}
