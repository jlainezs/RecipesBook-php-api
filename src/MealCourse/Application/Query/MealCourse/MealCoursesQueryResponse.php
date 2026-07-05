<?php
namespace App\MealCourse\Application\Query\MealCourse;

readonly final class MealCoursesQueryResponse
{
    public function __construct(
        /**
         * @var MealCourseDto[]
         */
        public array $items
    ){}
}
