<?php
namespace App\Recipe\Domain\ValueObjects;

use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;

final readonly class RecipeServings
{
    private int $servings;

    public function __construct(int $servings)
    {
        if ($servings <= 0)
        {
            throw new RecipeInvalidServingsException($servings);
        }

        $this->servings = $servings;
    }

    public function value(): int
    {
        return $this->servings;
    }

    public function __toString(): string
    {
        return (string) $this->servings;
    }
}
