<?php
namespace App\Ingredient\Application\Command\Ingredient\IngredientCreate;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class CreateIngredientCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        public string $description,

        #[Assert\Uuid]
        public string $ingredientTypeId
    ){}
}
