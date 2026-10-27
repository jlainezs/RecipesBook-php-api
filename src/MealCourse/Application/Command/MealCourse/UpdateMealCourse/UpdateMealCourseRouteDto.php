<?php
namespace App\MealCourse\Application\Command\MealCourse\UpdateMealCourse;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateMealCourseRouteDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
