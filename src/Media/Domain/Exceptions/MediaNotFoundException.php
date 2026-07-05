<?php
namespace App\Media\Domain\Exceptions;

use App\Shared\Domain\Exception\EntityNotFoundException;
use Throwable;

final class MediaNotFoundException extends EntityNotFoundException
{
    public function __construct(string $requiredId, ?Throwable $exception = null)
    {
        parent::__construct("Media with id $requiredId not found", 0, $exception);
    }
}
