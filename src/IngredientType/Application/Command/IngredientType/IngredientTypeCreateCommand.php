<?php
namespace App\IngredientType\Application\Command\IngredientType;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class IngredientTypeCreateCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name
    )
    {}
}
