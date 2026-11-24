<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourse;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly final class GetMealCourseQuery
{
    public function __construct(
        public string $id
    ){}
}
