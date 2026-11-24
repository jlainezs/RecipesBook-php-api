<?php
namespace App\MealCourse\Application\Command\MealCourse\CreateMealCourse;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly class CreateMealCourseCommand
{
    public function __construct(
        public string $id,
        public string $name
    ) {}
}
