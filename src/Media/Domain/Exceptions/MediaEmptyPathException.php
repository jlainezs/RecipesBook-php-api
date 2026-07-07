<?php
namespace App\Media\Domain\Exceptions;

use InvalidArgumentException;
use Throwable;

final class MediaEmptyPathException extends InvalidArgumentException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Media path cannot be empty', 0, $previous);
    }
}
