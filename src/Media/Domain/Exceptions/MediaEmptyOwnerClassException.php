<?php

namespace App\Media\Domain\Exceptions;

use InvalidArgumentException;
use Throwable;

final class MediaEmptyOwnerClassException extends InvalidArgumentException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Media owner class cannot be empty', 0, $previous);
    }
}
