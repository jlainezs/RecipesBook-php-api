<?php

namespace App\Media\Domain\Exceptions;

use InvalidArgumentException;
use Throwable;

final class MediaEmptyOwnerIdException extends InvalidArgumentException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Media owner id cannot be empty', 0, $previous);
    }
}
