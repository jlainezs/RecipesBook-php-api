<?php
namespace App\MealCourse\Application\Command\MealCourse;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class MealCourseUpdateCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name
    )
    {}
}
