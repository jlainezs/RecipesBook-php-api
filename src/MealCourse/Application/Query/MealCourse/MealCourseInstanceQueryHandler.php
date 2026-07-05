<?php

namespace App\MealCourse\Application\Query\MealCourse;

use App\MealCourse\Domain\Exceptions\MealCourseNotFoundException;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class MealCourseInstanceQueryHandler
{
    public function __construct(private MealCourseRepositoryInterface $repository)
    {}

    /**
     * @throws MealCourseNotFoundException
     */
    public function __invoke(MealCourseInstanceQuery $query): ?MealCourseInstanceResponse
    {
        if ($mealCourse = $this->repository->findOne($query->id))
        {
            return new MealCourseInstanceResponse(
                new MealCourseDto(
                    $mealCourse->getId()->toString(),
                    $mealCourse->getName(),
                    $mealCourse->getCreatedAt(),
                    $mealCourse->getUpdatedAt()
                )
            );
        }

        throw new MealCourseNotFoundException($query->id);
    }
}
