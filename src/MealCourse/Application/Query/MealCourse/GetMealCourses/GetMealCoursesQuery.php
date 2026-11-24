<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourses;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly final class GetMealCoursesQuery extends PaginatedListQuery
{
}
