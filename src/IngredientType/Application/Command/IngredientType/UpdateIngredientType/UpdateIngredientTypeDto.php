<?php
namespace App\IngredientType\Application\Command\IngredientType\UpdateIngredientType;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateIngredientTypeDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name
    ) {}
}
