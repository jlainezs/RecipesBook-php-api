<?php

namespace App\MealCourse\Application\Query\MealCourse\GetMealCourse;

use App\MealCourse\Application\Query\MealCourse\MealCourseDto;

final readonly class GetMealCourseResponse
{
    public function __construct(
        public ?MealCourseDto $mealCourse
    ){}
}
