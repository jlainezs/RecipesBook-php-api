<?php
namespace App\MealCourse\Application\Command\MealCourse\CreateMealCourse;

final readonly class CreateMealCourseDto
{
    public function __construct(
        public string $name
    ){}
}
