<?php
namespace App\IngredientType\Application\Command\IngredientType\UpdateIngredientType;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateIngredientTypeCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\NotBlank]
        public string $name
    ){}
}
