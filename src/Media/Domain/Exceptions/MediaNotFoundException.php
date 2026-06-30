<?php
namespace App\Media\Domain\Exceptions;

use Exception;
use Throwable;

final class MediaNotFoundException extends Exception
{
    public function __construct(string $requiredId, ?Throwable $exception = null)
    {
        parent::__construct("Media with id $requiredId not found", 0, $exception);
    }
}
