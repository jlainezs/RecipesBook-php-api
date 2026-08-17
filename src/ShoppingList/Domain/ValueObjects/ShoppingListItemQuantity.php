<?php
namespace App\ShoppingList\Domain\ValueObjects;

use App\ShoppingList\Domain\Exceptions\InvalidShoppingListItemQuantity;

final readonly class ShoppingListItemQuantity
{
    private float $value;

    public function __construct(float $value)
    {
        if ($value < 0)
        {
            throw new InvalidShoppingListItemQuantity();
        }

        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function equals(ShoppingListItemQuantity $other): bool
    {
        return $this->value === $other->value;
    }
}
