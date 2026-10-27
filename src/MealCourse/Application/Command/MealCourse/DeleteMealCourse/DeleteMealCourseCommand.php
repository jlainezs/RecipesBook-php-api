<?php
namespace App\MealCourse\Application\Command\MealCourse\DeleteMealCourse;

readonly final class DeleteMealCourseCommand
{
    public function __construct(
        public  string $id
    ){}
}
