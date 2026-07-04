<?php
namespace App\IngredientType\Application\Query\IngredientType;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class IngredientTypeInstanceQuery
{
    #[Assert\Uuid]
    public function __construct(
        public string $id
    ){}
}
