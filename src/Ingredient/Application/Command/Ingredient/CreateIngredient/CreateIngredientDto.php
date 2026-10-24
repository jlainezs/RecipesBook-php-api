<?php
namespace App\Ingredient\Application\Command\Ingredient\CreateIngredient;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateIngredientDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,

        #[Assert\Uuid]
        public string $ingredientTypeId,

        public string $description,
    ){}
}
