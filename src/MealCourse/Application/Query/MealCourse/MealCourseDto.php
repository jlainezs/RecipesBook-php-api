<?php
namespace App\MealCourse\Application\Query\MealCourse;

use App\MealCourse\Domain\Model\MealCourse;
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

    public static function fromDomain(MealCourse $mealCourse): self
    {
        return new self(
            $mealCourse->getId()->toString(),
            $mealCourse->getName(),
            $mealCourse->getCreatedAt(),
            $mealCourse->getUpdatedAt()
        );
    }
}
