<?php
namespace App\MealCourse\Application\Command\MealCourse\UpdateMealCourse;

final readonly class MealCourseUpdateDto
{
    public function __construct(
        public string $name
    ){}
}
