<?php

namespace App\IngredientType\Application\Query\IngredientType;

final readonly class IngredientTypesQueryResponse
{
    public function __construct(
        /**
         * @var IngredientTypeDto[]
         */
        public readonly array $items,
    ) {}
}
