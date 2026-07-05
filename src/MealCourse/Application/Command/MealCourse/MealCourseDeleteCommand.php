<?php
namespace App\MealCourse\Application\Command\MealCourse;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class MealCourseDeleteCommand
{
    public function __construct(
        #[Assert\Uuid]
        public  string $id
    ){}
}
