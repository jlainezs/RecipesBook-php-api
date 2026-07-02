<?php
namespace App\Media\Domain\Exceptions;

use Exception;
use Throwable;

final class MediaEmptyContentException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Media content cannot be empty', 0, $previous);
    }
}
