<?php
namespace App\MealCourse\Application\Command\MealCourse\UpdateMealCourse;

class UpdateMealCourseCommandFactory
{
    public function createFromRequest(UpdateMealCourseRouteDto $routeDto, UpdateMealCourseDto $updateDto): UpdateMealCourseCommand
    {
        return new UpdateMealCourseCommand($routeDto->id, $updateDto->name);
    }
}
