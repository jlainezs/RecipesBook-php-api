<?php
namespace App\MealCourse\Infrastructure\Repository;

use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class MealCourseRepository extends ServiceEntityRepository implements MealCourseRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MealCourse::class);
    }

    public function save(MealCourse $mealCourse): void
    {
        $this->getEntityManager()->persist($mealCourse);
        $this->getEntityManager()->flush();
    }

    public function findOne(string $id): ?MealCourse
    {
        return $this->find($id);
    }

    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy([], null, $limit, $offset);
    }

    public function delete(MealCourse $mealCourse): void
    {
        $this->getEntityManager()->remove($mealCourse);
        $this->getEntityManager()->flush();
    }
}
