<?php
namespace App\Ingredient\Application\Command\Ingredient\UpdateIngredient;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateIngredientDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,

        public string $description,

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $ingredientTypeId
    ){}
}
