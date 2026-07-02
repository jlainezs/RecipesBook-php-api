<?php

namespace App\Media\Domain\Exceptions;

use Exception;
use Throwable;

final class MediaInvalidOwnerIdException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Provided media owner id is not a valid value', 0, $previous);
    }
}
