<?php
namespace App\Recipe\Domain\Repository;
use App\Recipe\Domain\Model\Recipe;

interface RecipeIngredientRepositoryInterface
{
    public function findIngredients(string $recipeId): array;
    public function save(Recipe $recipe, iterable $recipeIngredients): void;
    public function delete(iterable $recipeIngredients): void;
}
