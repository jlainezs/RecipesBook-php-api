<?php
namespace App\Media\Domain\Exceptions;

use Exception;
use Throwable;

final class EmptyUriException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct("Uri cannot be empty", 0, $previous);
    }
}
