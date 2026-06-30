<?php

namespace App\Media\Domain\Exceptions;

use Exception;
use Throwable;

final class EmptyOwnerIdException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct("Owner id cannot be empty", 0, $previous);
    }
}
