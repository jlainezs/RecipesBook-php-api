<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourse;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class GetMealCourseQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
