<?php

namespace App\Media\Domain\Exceptions;

use Exception;
use Throwable;

final class MediaEmptyOwnerIdException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Media owner id cannot be empty', 0, $previous);
    }
}
