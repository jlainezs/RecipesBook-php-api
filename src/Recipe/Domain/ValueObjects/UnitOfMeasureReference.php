<?php

namespace App\Recipe\Domain\ValueObjects;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;

final readonly class UnitOfMeasureReference
{
    private AggregateRootId $id;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function __construct(string $id)
    {
        $this->id = new AggregateRootId($id);;
    }

    public function value(): AggregateRootId
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->value()->__toString();
    }

    public function equals(UnitOfMeasureReference $other): bool
    {
        return $this->value()->toString() === $other->value()->toString();
    }
}
