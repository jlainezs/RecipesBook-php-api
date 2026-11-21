<?php
namespace App\Recipe\Application\Query\Recipe;

use App\Recipe\Domain\Model\RecipeIngredient;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class RecipeIngredientDto
{
    public function __construct(
        #[Assert\Uuid]
        public ?string $id,
        #[Assert\Uuid]
        public ?string $recipeId,
        #[Assert\Uuid]
        public ?string $ingredientId,
        #[Assert\Uuid]
        public ?string $unitOfMeasureId,
        public int $ordering,
        public float $quantity,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ) {}

    public static function fromDomain(RecipeIngredient $recipeIngredient): self
    {
        return new self(
            $recipeIngredient->getId()->toString(),
            $recipeIngredient->getRecipe()->getId()->toString(),
            $recipeIngredient->getIngredient()->value(),
            $recipeIngredient->getUnitOfMeasure()->value(),
            $recipeIngredient->getOrdering()->value(),
            $recipeIngredient->getQuantity()->value(),
            $recipeIngredient->getCreatedAt(),
            $recipeIngredient->getUpdatedAt(),
        );
    }
}
