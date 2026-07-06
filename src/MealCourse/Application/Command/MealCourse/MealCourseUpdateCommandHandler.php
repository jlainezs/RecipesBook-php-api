<?php
namespace App\MealCourse\Application\Command\MealCourse;

use App\MealCourse\Domain\Exceptions\MealCourseEmptyNameException;
use App\MealCourse\Domain\Exceptions\MealCourseNotFoundException;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class MealCourseUpdateCommandHandler
{
    public function __construct(private MealCourseRepositoryInterface $repository)
    {}

    /**
     * @throws MealCourseNotFoundException
     * @throws MealCourseEmptyNameException
     */
    public function __invoke(MealCourseUpdateCommand $command): void
    {
        if ($mealCourse = $this->repository->findOne($command->id))
        {
            $mealCourse->rename($command->name);
            $this->repository->save($mealCourse);
        }
        else
        {
            throw new MealCourseNotFoundException($command->id);
        }
    }
}
