<?php

namespace App\MealCourse\Application\Query\MealCourse\GetMealCourse;

use App\MealCourse\Application\Query\MealCourse\MealCourseDto;
use App\MealCourse\Domain\Exceptions\MealCourseNotFoundException;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetMealCourseQueryHandler
{
    public function __construct(private MealCourseRepositoryInterface $repository)
    {}

    /**
     * @throws MealCourseNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(GetMealCourseQuery $query): ?GetMealCourseResponse
    {
        if ($mealCourse = $this->repository->findOne(new AggregateRootId($query->id)))
        {
            return new GetMealCourseResponse(
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
