<?php

namespace App\Season\Domain\Exceptions;

use Exception;
use Throwable;

final class SeasonEmptyNameException extends Exception
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
