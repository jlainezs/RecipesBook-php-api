<?php
namespace App\IngredientType\Application\Query\IngredientType;

final readonly class FindIngredientTypeReferenceQuery
{
    public function __construct(public string $ingredientTypeId)
    {}
}
