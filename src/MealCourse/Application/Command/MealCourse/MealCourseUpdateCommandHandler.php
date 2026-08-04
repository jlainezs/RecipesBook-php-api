<?php
namespace App\MealCourse\Application\Command\MealCourse;

use App\MealCourse\Domain\Exceptions\MealCourseEmptyNameException;
use App\MealCourse\Domain\Exceptions\MealCourseNotFoundException;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class MealCourseUpdateCommandHandler
{
    public function __construct(private MealCourseRepositoryInterface $repository)
    {}

    /**
     * @throws MealCourseNotFoundException
     * @throws MealCourseEmptyNameException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(MealCourseUpdateCommand $command): void
    {
        if ($mealCourse = $this->repository->findOne(new AggregateRootId($command->id)))
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
