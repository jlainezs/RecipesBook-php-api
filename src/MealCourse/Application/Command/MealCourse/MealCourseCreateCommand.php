<?php
namespace App\MealCourse\Application\Command\MealCourse;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class MealCourseCreateCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name
    ) {
    }
}
