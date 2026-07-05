<?php
namespace App\Season\Domain\Exceptions;

use App\Shared\Domain\Exception\EntityNotFoundException;
use Throwable;

final class SeasonNotFoundException extends EntityNotFoundException
{
    public function __construct(string $requestedId = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('Season with id "%s" not found', $requestedId);
        parent::__construct($message, $code, $previous);
    }
}
