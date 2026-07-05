<?php
namespace App\UnitOfMeasure\Domain\Exceptions;

use App\Shared\Domain\Exception\EntityNotFoundException;
use Throwable;

class UnitOfMeasureNotFoundException extends EntityNotFoundException
{
    public function __construct(string $requestedId = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('Unit of measure with id "%s" not found', $requestedId);
        parent::__construct($message, $code, $previous);
    }
}
