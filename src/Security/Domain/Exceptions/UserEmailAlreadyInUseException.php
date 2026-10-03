<?php
namespace App\Security\Domain\Exceptions;

use Exception;
use Throwable;

final class UserEmailAlreadyInUseException extends Exception
{
    public function __construct(string $email = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = (!empty($email))
            ? sprintf("Email '%s' already in use by another user.", $email)
            : "Email already in use by another user.";
        parent::__construct($message, $code, $previous);
    }
}
