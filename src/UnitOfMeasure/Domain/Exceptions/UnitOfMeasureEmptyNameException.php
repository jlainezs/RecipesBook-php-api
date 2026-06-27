<?php

namespace App\UnitOfMeasure\Domain\Exceptions;

use Exception;

final class UnitOfMeasureEmptyNameException extends Exception
{
    public function __construct(){
        parent::__construct('Unit of measure name cannot be empty', 0);
    }
}
