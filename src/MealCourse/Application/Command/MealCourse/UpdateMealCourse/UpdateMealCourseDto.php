<?php
namespace App\MealCourse\Application\Command\MealCourse\UpdateMealCourse;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateMealCourseDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name
    ){}
}
