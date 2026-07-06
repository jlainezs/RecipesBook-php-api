<?php
namespace App\MealCourse\Infrastructure;

use App\MealCourse\Application\Service\MealCourseItemsPager;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;

final readonly class DoctrineMealCourseListPager implements MealCourseItemsPager
{
    public function __construct(private MealCourseRepositoryInterface $repository)
    {}

    public function items(int $offset = 0, int $limit = 20): array
    {
        return $this->repository->findAll($limit, $offset);
    }
}
