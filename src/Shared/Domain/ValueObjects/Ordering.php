<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidOrderingException;

final readonly class Ordering
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidOrderingException("Negative ordering is not allowed.");
        }

        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }
}
