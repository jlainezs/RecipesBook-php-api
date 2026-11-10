<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourse;

class GetMealCourseQueryFactory
{
    public function createFromDto(GetMealCourseRouteDto $dto): GetMealCourseQuery
    {
        return new GetMealCourseQuery($dto->id);
    }
}
