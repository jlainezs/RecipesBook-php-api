<?php
namespace App\MealCourse\Application\Command\MealCourse;

use App\MealCourse\Domain\Exceptions\MealCourseNotFoundException;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class MealCourseDeleteCommandHandler
{
    public function __construct(private MealCourseRepositoryInterface $repository)
    {}

    /**
     * @throws MealCourseNotFoundException
     */
    public function __invoke(MealCourseDeleteCommand $command): void
    {
        if ($mealCourse = $this->repository->findOne($command->id))
        {
            $this->repository->delete($mealCourse);
        }
        else
        {
            throw new MealCourseNotFoundException($command->id);
        }
    }
}
