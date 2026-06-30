<?php

namespace App\Media\Domain\Exceptions;

use Exception;
use Throwable;

final class InvalidOwnerIdException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct("Owner id is not an UUID value", 0, $previous);
    }
}
