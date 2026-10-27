<?php
namespace App\MealCourse\Application\Command\MealCourse\DeleteMealCourse;

use Symfony\Component\Validator\Constraints as Assert;
class DeleteMealCourseDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
