<?php
namespace App\Ingredient\Application\Command\Ingredient;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class IngredientCreateCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        public string $description,

        #[Assert\Uuid]
        public string $ingredientTypeId
    ){}
}
