<?php
namespace App\MealCourse\Application\Command\MealCourse\CreateMealCourse;

use App\MealCourse\Domain\Exceptions\MealCourseEmptyNameException;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateMealCourseCommandHandler
{
    public function __construct(
        private MealCourseRepositoryInterface $repository
    ){}

    /**
     * @throws MealCourseEmptyNameException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(CreateMealCourseCommand $command): void
    {
        $mealCourse = MealCourse::create($command->name);
        $this->repository->save($mealCourse);
    }
}
