<?php
namespace App\Media\Domain\Exceptions;

use InvalidArgumentException;
use Throwable;

final class MediaEmptyMimeTypeException extends InvalidArgumentException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Media mime type cannot be empty', 0, $previous);
    }
}
