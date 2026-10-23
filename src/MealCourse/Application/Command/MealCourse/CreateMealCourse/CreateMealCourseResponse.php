<?php
namespace App\MealCourse\Application\Command\MealCourse\CreateMealCourse;

final readonly class CreateMealCourseResponse
{
    public function __construct(
        public string $id
    ){}
}
