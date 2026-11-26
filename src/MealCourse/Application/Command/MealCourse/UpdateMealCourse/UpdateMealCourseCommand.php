<?php
namespace App\MealCourse\Application\Command\MealCourse\UpdateMealCourse;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
final readonly class UpdateMealCourseCommand
{
    public function __construct(
        public string $id,
        public string $name
    )
    {}
}
