<?php
namespace App\Season\Domain\Exceptions;

use App\Shared\Domain\Exceptions\EntityNotFoundException;
use Throwable;

final class SeasonNotFoundException extends EntityNotFoundException
{
    public function __construct(string $userIdentifier = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf("Season with id '%s' not found", $userIdentifier);
        parent::__construct($message, $code, $previous);
    }
}
