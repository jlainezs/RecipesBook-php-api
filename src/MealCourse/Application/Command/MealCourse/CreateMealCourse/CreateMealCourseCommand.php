<?php
namespace App\MealCourse\Application\Command\MealCourse\CreateMealCourse;

readonly class CreateMealCourseCommand
{
    public function __construct(
        public string $id,
        public string $name
    ) {}
}
