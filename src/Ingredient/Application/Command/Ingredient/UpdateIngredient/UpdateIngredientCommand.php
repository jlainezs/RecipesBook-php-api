<?php
namespace App\Ingredient\Application\Command\Ingredient\UpdateIngredient;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateIngredientCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\NotBlank]
        public string $name,

        public string $description,

        #[Assert\Uuid]
        public string $ingredientTypeId
    ){}
}
