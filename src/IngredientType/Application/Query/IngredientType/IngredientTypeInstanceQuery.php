<?php
namespace App\IngredientType\Application\Query\IngredientType;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class IngredientTypeInstanceQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
