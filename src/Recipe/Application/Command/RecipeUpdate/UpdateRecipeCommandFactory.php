<?php
namespace App\Recipe\Application\Command\RecipeUpdate;

class UpdateRecipeCommandFactory
{
    public function createFromRequestDto(UpdateRecipeRouteDto $routeDto, UpdateRecipeDto $request): UpdateRecipeCommand
    {
        $name = $request->name;
        $description = $request->description;
        $source = $request->source;
        $servings = $request->servings;
        $rating = $request->rating;
        $steps = [];
        $ingredients = [];

        foreach ($request->steps as $step)
        {
            $steps[] = new UpdateRecipeStepDto(
                id: $step['id'] ?? null,
                description: $step['description'],
                ordering: $step['ordering']
            );
        }

        foreach ($request->ingredients as $ingredient)
        {
            $ingredients[] = new UpdateRecipeIngredientDto(
                id: $ingredient['id'] ?? null,
                recipeId: $routeId->id,
                ingredientId: $ingredient['ingredientId'] ?? null,
                unitOfMeasureId: $ingredient['unitOfMeasureId'] ?? null,
                ordering: $ingredient['ordering'],
                quantity: $ingredient['quantity']
            );
        }

        return new UpdateRecipeCommand(
            id: $routeDto->id,
            name: $request->name,
            servings: $request->servings,
            rating: $request->rating,
            description: $request->description,
            source: $request->source,
            steps: $steps,
            ingredients: $ingredients
        );
    }
}
