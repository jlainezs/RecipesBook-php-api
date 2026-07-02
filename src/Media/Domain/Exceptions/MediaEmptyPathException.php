<?php
namespace App\Media\Domain\Exceptions;

use Exception;
use Throwable;

final class MediaEmptyPathException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Media path cannot be empty', 0, $previous);
    }
}
