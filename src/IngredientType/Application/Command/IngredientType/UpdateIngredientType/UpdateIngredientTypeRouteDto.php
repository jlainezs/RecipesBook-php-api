<?php
namespace App\IngredientType\Application\Command\IngredientType\UpdateIngredientType;

use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateIngredientTypeRouteDto
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $id
    ){}
}
