<?php
namespace App\Ingredient\Application\Query\Ingredient;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class IngredientInstanceQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
