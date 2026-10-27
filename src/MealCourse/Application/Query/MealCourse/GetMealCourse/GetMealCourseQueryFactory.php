<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourse;

class GetMealCourseQueryFactory
{
    public function createFromDto(GetMealCourseDto $dto): GetMealCourseQuery
    {
        return new GetMealCourseQuery($dto->id);
    }
}
