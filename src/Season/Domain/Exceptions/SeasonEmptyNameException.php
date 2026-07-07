<?php

namespace App\Season\Domain\Exceptions;

use InvalidArgumentException;
use Throwable;

final class SeasonEmptyNameException extends InvalidArgumentException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            "Season name is empty",
            0,
            $previous
        );
    }
}
