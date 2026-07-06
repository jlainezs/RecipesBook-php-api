<?php

namespace App\MealCourse\Application\Query\MealCourse;

final readonly class MealCourseInstanceResponse
{
    public function __construct(
        public ?MealCourseDto $mealCourse
    ){}
}
