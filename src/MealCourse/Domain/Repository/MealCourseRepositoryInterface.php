<?php
namespace App\MealCourse\Domain\Repository;

use App\MealCourse\Domain\Model\MealCourse;
use App\Shared\Domain\ValueObject\AggregateRootId;

interface MealCourseRepositoryInterface
{
    public function findOne(AggregateRootId $id): ?MealCourse;
    public function findAll(?int $limit = null, ?int $offset = null): array;

    public function save(MealCourse $mealCourse): void;

    public function delete(MealCourse $mealCourse): void;
}
