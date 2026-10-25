<?php
namespace App\Ingredient\Application\Command\Ingredient\UpdateIngredient;

use Symfony\Component\Validator\Constraints as Assert;
final readonly class UpdateIngredientRouteDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
