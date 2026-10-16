<?php
namespace App\Ingredient\Application\Command\Ingredient\CreateIngredient;

final readonly class CreateMealCourseResponse
{
    public function __construct(
        public string $id
    ){}
}
