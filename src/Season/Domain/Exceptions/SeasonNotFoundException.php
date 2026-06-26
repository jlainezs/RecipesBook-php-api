<?php
namespace App\Season\Domain\Exceptions;

use Exception;
use Throwable;

final class SeasonNotFoundException extends Exception
{
    public function __construct(string $requestedId = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('Season with id "%s" not found', $requestedId);
        parent::__construct($message, $code, $previous);
    }
}
