<?php

namespace App\UnitOfMeasure\Infrastructure\Repository;

use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UnitOfMeasureRepository extends ServiceEntityRepository implements UnitOfMeasureRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnitOfMeasure::class);
    }

    public function findOne(string $id): ?UnitOfMeasure
    {
        return parent::find($id);
    }

    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy([], null, $limit, $offset);
    }

    public function save(UnitOfMeasure $unitOfMeasure): void
    {
        $this->getEntityManager()->persist($unitOfMeasure);
        $this->getEntityManager()->flush();
    }

    public function delete(UnitOfMeasure $unitOfMeasure): void
    {
        $this->getEntityManager()->remove($unitOfMeasure);
        $this->getEntityManager()->flush();
    }

}
