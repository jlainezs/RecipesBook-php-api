<?php
namespace App\MealCourse\Application\Command\MealCourse\CreateMealCourse;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly class CreateMealCourseCommand
{
    public function __construct(
        public string $id,
        public string $name
    ) {}
}
