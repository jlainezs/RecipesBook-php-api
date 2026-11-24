<?php
namespace App\IngredientType\Application\Query\IngredientTypeReference\FindIngredientTypeReference;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class FindIngredientTypeReferenceQuery
{
    public function __construct(public string $ingredientTypeId)
    {}
}
