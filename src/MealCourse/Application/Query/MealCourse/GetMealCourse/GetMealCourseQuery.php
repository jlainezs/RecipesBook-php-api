<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourse;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly final class GetMealCourseQuery
{
    public function __construct(
        public string $id
    ){}
}
