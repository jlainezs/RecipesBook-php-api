<?php

namespace App\Security\Domain\Exceptions;

use App\Shared\Domain\Exceptions\EntityNotFoundException;
use Throwable;

final class UserNotFoundException extends EntityNotFoundException
{
    public function __construct(string $userIdentifier = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf("User identified by '%s' not found", $userIdentifier);
        parent::__construct($message, $code, $previous);
    }
}
