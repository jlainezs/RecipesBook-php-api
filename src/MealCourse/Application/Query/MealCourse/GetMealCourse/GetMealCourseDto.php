<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourse;

use Symfony\Component\Validator\Constraints as Assert;

class GetMealCourseDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
