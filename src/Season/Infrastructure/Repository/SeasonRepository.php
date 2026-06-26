<?php

namespace App\Season\Infrastructure\Repository;

use App\IngredientType\Domain\Model\IngredientType;
use App\Season\Domain\Model\Season;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SeasonRepository extends ServiceEntityRepository implements SeasonRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientType::class);
    }
    public function findOne(string $id): ?Season
    {
        return parent::find($id);
    }

    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy([], [], $limit, $offset);
    }

    public function save(Season $season): void
    {
        $this->getEntityManager()->persist($season);
        $this->getEntityManager()->flush();
    }

    public function delete(Season $season): void
    {
        $this->getEntityManager()->remove($season);
        $this->getEntityManager()->flush();
    }
}
