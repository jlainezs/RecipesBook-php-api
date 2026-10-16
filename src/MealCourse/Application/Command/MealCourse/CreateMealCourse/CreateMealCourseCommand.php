<?php
namespace App\MealCourse\Application\Command\MealCourse\CreateMealCourse;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateMealCourseCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name
    ) {
    }
}
