<?php
namespace App\MealCourse\Application\Command\MealCourse\UpdateMealCourse;

final readonly class UpdateMealCourseCommand
{
    public function __construct(
        public string $id,
        public string $name
    )
    {}
}
