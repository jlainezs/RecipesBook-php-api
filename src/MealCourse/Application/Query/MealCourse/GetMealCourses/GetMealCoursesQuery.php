<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourses;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly final class GetMealCoursesQuery extends PaginatedListQuery
{
}
