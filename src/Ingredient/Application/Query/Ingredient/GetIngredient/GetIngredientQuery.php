<?php
namespace App\Ingredient\Application\Query\Ingredient\GetIngredient;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class GetIngredientQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
