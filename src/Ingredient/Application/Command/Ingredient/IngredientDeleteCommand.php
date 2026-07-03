<?php
namespace App\Ingredient\Application\Command\Ingredient;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class IngredientDeleteCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
