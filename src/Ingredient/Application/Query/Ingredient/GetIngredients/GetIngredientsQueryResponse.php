<?php
namespace App\Ingredient\Application\Query\Ingredient\GetIngredients;

use App\Ingredient\Application\Query\Ingredient\IngredientDto;

final readonly class GetIngredientsQueryResponse
{
    public function __construct(
        /**
         * @var IngredientDto[]
         */
        public array $items
    ){}
}
