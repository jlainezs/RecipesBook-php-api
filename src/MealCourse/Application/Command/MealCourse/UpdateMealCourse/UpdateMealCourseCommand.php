<?php
namespace App\MealCourse\Application\Command\MealCourse\UpdateMealCourse;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class UpdateMealCourseCommand
{
    public function __construct(
        public string $id,
        public string $name
    )
    {}
}
