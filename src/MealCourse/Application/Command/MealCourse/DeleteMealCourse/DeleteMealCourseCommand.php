<?php
namespace App\MealCourse\Application\Command\MealCourse\DeleteMealCourse;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly final class DeleteMealCourseCommand
{
    public function __construct(
        public  string $id
    ){}
}
