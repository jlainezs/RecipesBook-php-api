<?php
namespace App\MealCourse\Application\Command\MealCourse;

use App\MealCourse\Domain\Exceptions\MealCourseNotFoundException;
use App\MealCourse\Infrastructure\Repository\MealCourseRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class MealCourseUpdateCommandHandler
{
    public function __construct(private MealCourseRepository $mealCourseRepository)
    {}

    /**
     * @throws MealCourseNotFoundException
     */
    public function __invoke(MealCourseUpdateCommand $command): void
    {
        if ($mealCourse = $this->mealCourseRepository->get($command->id))
        {
            $mealCourse->rename($command->name);
        }
        else
        {
            throw new MealCourseNotFoundException($command->id);
        }
    }
}
