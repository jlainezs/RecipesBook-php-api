<?php
namespace App\MealCourse\Application\Command\MealCourse\DeleteMealCourse;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class DeleteMealCourseCommand
{
    public function __construct(
        #[Assert\Uuid]
        public  string $id
    ){}
}
