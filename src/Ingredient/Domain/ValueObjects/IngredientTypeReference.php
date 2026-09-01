<?php
namespace App\Ingredient\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;

final readonly class IngredientTypeReference
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

    public function equals(IngredientTypeReference $other): bool
    {
        return $this->id->toString() == $other->value()->toString();
    }
}
