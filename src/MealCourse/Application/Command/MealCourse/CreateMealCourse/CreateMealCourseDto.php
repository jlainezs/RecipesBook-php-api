<?php
namespace App\MealCourse\Application\Command\MealCourse\CreateMealCourse;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateMealCourseDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name
    ){}
}
