<?php
namespace App\Ingredient\Application\Query\Ingredient;

final readonly class IngredientsQueryResponse
{
    public function __construct(
        /**
         * @var IngredientDto[]
         */
        public readonly array $items
    ){}
}
