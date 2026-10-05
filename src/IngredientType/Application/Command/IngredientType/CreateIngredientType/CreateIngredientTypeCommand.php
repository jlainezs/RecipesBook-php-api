<?php
namespace App\IngredientType\Application\Command\IngredientType\CreateIngredientType;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class CreateIngredientTypeCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name
    )
    {}
}
