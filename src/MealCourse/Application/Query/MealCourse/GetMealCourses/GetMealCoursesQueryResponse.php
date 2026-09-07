<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourses;

use App\MealCourse\Application\Query\MealCourse\MealCourseDto;

readonly final class GetMealCoursesQueryResponse
{
    public function __construct(
        /**
         * @var MealCourseDto[]
         */
        public array $items
    ){}
}
