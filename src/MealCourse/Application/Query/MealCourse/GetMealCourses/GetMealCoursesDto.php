<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourses;

final readonly class GetMealCoursesDto
{
    public function __construct(
        public int $offset = 0,
        public int $limit = 20
    ){}
}
