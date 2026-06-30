<?php
namespace App\Media\Domain\Exceptions;

use Exception;
use Throwable;

final class EmptyMimeTypeException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct("Media mime type cannot be empty", 0, $previous);
    }
}
