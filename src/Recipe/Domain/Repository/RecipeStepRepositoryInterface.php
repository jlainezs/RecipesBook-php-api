<?php
namespace App\Recipe\Domain\Repository;

use App\Recipe\Domain\Model\Recipe;

interface RecipeStepRepositoryInterface
{
    public function findSteps(string $recipeId): array;
    public function save(Recipe $recipe, iterable $recipeSteps): void;
    public function delete(iterable $recipeSteps): void;
}
