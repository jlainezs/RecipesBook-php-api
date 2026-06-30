<?php

namespace App\Media\Domain\Exceptions;

use Exception;
use Throwable;

final class EmptyOwnerClassException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct("Owner class cannot be empty", 0, $previous);
    }
}
