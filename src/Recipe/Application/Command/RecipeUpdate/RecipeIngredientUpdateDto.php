<?php
namespace App\Recipe\Application\Command\RecipeUpdate;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class RecipeIngredientUpdateDto
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
    ){}
}
