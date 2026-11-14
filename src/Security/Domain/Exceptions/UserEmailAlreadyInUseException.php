<?php
namespace App\Security\Domain\Exceptions;

use App\Shared\Domain\Exceptions\ConflictException;
use Throwable;

final class UserEmailAlreadyInUseException extends ConflictException
{
    public function __construct(string $email = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = (!empty($email))
            ? sprintf("Email '%s' already in use by another user.", $email)
            : "Email already in use by another user.";
        parent::__construct($message, $code, $previous);
    }
}
