<?php
namespace App\MealCourse\Application\Command\MealCourse\DeleteMealCourse;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly final class DeleteMealCourseCommand
{
    public function __construct(
        public  string $id
    ){}
}
