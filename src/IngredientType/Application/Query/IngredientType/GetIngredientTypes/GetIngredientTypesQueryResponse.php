<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientTypes;

use App\IngredientType\Application\Query\IngredientType\IngredientTypeDto;

final readonly class GetIngredientTypesQueryResponse
{
    public function __construct(
        /**
         * @var IngredientTypeDto[]
         */
        public array $items,
    ) {}
}
