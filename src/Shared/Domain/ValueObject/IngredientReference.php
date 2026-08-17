<?php
namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;

final readonly class IngredientReference
{
    private AggregateRootId $id;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function __construct(string $id)
    {
        $this->id = new AggregateRootId($id);
    }

    public function value(): AggregateRootId
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->value()->__toString();
    }

    public function equals(IngredientReference $other): bool
    {
        return $this->value()->toString() === $other->value()->toString();
    }
}
