<?php

namespace App\UnitOfMeasure\Domain\Exceptions;

use InvalidArgumentException;

final class UnitOfMeasureEmptyNameException extends InvalidArgumentException
{
    public function __construct(){
        parent::__construct('Unit of measure name cannot be empty', 0);
    }
}
