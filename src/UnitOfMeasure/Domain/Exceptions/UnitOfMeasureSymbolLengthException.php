<?php
namespace App\UnitOfMeasure\Domain\Exceptions;

use App\UnitOfMeasure\Infrastructure\Doctrine\Type\UnitOfMeasureSymbolType;
use Exception;

class UnitOfMeasureSymbolLengthException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            sprintf('Unit of measure symbol length must be less than or equal to %s' , UnitOfMeasureSymbolType::DEFAULT_LENGTH)
        );
    }
}
