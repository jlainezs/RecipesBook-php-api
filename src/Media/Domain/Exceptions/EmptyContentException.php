<?php
namespace App\Media\Domain\Exceptions;

use Exception;
use Throwable;

final class EmptyContentException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct("Content cannot be empty", 0, $previous);
    }
}
