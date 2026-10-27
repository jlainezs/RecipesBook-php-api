<?php
namespace App\MealCourse\Application\Command\MealCourse\DeleteMealCourse;

class DeleteMealCourseCommandFactory
{
    public function createFromDto(DeleteMealCourseDto $dto): DeleteMealCourseCommand
    {
        return new DeleteMealCourseCommand($dto->id);
    }
}
