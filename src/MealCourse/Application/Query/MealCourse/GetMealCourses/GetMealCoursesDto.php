<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourses;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetMealCoursesDto
{
    public function __construct(
        #[Assert\PositiveOrZero]
        public int $offset = 0,
        #[Assert\Positive]
        public int $limit = 20
    ){}
}
