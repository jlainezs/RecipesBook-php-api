<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientType;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class GetIngredientTypeQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
