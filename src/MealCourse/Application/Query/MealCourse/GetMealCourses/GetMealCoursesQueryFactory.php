<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourses;

class GetMealCoursesQueryFactory
{
    public function createFromDto(GetMealCoursesDto $dto): GetMealCoursesQuery
    {
        return new GetMealCoursesQuery($dto->page->offset, $dto->page->limit);
    }
}
