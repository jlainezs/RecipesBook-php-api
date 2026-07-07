<?php

namespace App\Media\Domain\Exceptions;

use InvalidArgumentException;
use Throwable;

final class MediaInvalidOwnerIdException extends InvalidArgumentException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Provided media owner id is not a valid value', 0, $previous);
    }
}
