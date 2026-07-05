<?php
namespace App\MealCourse\Application\Query\MealCourse;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class MealCourseInstanceQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
