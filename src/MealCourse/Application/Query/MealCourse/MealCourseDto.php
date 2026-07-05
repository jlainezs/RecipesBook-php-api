<?php
namespace App\MealCourse\Application\Query\MealCourse;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class MealCourseDto
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ){}
}
