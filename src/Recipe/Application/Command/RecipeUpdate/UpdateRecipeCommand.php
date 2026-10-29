<?php
namespace App\Recipe\Application\Command\RecipeUpdate;


final readonly class UpdateRecipeCommand
{
    public function __construct(
        public string $id,
        public string $name,
        public int $servings,
        public int $rating,
        public ?string $description,
        public ?string $source,
        public iterable $steps,
        public iterable $ingredients,
    ){}
}
