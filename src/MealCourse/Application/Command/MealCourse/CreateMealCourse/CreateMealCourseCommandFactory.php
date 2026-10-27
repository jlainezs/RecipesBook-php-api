<?php
namespace App\MealCourse\Application\Command\MealCourse\CreateMealCourse;

use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;

readonly class CreateMealCourseCommandFactory
{
    public function __construct(
        public ApplicationDataValidator $validator
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function createFromDto(CreateMealCourseDto $dto): CreateMealCourseCommand
    {
        $id = AggregateRootId::generateId();
        $cmd = new CreateMealCourseCommand(
            $id->toString(),
            $dto->name
        );
        $this->validator->validate($cmd);

        return $cmd;
    }
}
