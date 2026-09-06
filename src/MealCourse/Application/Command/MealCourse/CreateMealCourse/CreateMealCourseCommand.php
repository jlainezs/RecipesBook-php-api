<?php
namespace App\MealCourse\Application\Command\MealCourse\CreateMealCourse;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class CreateMealCourseCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name
    ) {
    }
}
