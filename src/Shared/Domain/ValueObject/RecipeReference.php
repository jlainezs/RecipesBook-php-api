<?php
namespace App\Shared\Domain\ValueObject;

final readonly class RecipeReference
{
    private AggregateRootId $id;

    public function __construct(AggregateRootId $id)
    {
        $this->id = $id;
    }

    public function value(): AggregateRootId
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->value()->__toString();
    }

    public function equals(RecipeReference $other): bool
    {
        return $this->value()->toString() === $other->value()->toString();
    }
}
