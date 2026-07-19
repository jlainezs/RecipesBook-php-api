<?php
namespace App\Recipe\Domain\ValueObjects;

use App\Recipe\Domain\Exceptions\RecipeInvalidRatingException;

final readonly class RecipeRating
{
    private ?int $rating;

    public function __construct(?int $rating)
    {
        if ($rating !== null && ($rating < 1 || $rating > 5)) {
            throw new RecipeInvalidRatingException($rating);
        }

        $this->rating = $rating;
    }

    public function value(): ?int
    {
        return $this->rating;
    }

    public function __toString(): string
    {
        return (string) $this->rating;
    }
}
