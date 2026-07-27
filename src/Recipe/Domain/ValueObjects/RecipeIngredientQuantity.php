<?php
namespace App\Recipe\Domain\ValueObjects;
use App\Recipe\Domain\Exceptions\RecipeIngredientInvalidQuantityException;

final readonly class RecipeIngredientQuantity
{
    private ?float $quantity;

    public function __construct(?float $quantity)
    {
        if ($quantity < 0)
        {
            throw new RecipeIngredientInvalidQuantityException();
        }

        $this->quantity = $quantity;
    }

    public function value(): ?float
    {
        return $this->quantity;
    }

    public function __toString(): string
    {
        return (string) $this->quantity;
    }
}
