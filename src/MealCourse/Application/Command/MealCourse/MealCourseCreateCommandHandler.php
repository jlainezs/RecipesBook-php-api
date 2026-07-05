<?php
namespace App\MealCourse\Application\Command\MealCourse;

use App\MealCourse\Domain\Exceptions\MealCourseEmptyNameException;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class MealCourseCreateCommandHandler
{
    public function __construct(
        private MealCourseRepositoryInterface $repository
    ){}

    /**
     * @throws MealCourseEmptyNameException
     */
    public function __invoke(MealCourseCreateCommand $command): void
    {
        $mealCourse = MealCourse::create($command->name);
        $this->repository->save($mealCourse);
    }

}
