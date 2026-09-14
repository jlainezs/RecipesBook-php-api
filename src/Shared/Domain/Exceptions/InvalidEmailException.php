<?php
namespace App\Shared\Domain\Exceptions;

use InvalidArgumentException;

class InvalidEmailException extends  InvalidArgumentException
{
    public function __construct(string $email, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf("The email '%s' is not valid", $email);
        parent::__construct($message, $code, $previous);
    }
}
