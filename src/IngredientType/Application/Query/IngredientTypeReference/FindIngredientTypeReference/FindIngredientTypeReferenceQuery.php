<?php
namespace App\IngredientType\Application\Query\IngredientTypeReference\FindIngredientTypeReference;

final readonly class FindIngredientTypeReferenceQuery
{
    public function __construct(public string $ingredientTypeId)
    {}
}
