<?php
namespace App\Shared\Domain\Exception;
use Exception;
use Throwable;

class EmptyIdNotAllowedException extends Exception
{
    public function __construct(string $message = "Empty Id is not allowed", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
