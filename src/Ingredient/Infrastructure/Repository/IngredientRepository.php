<?php

namespace App\Ingredient\Infrastructure\Repository;

use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IngredientRepository extends ServiceEntityRepository implements IngredientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function save(Ingredient $ingredient): void
    {
        $this->getEntityManager()->persist($ingredient);
        $this->getEntityManager()->flush();
    }

    public function findOne(string $id): ?Ingredient
    {
        return parent::find($id);
    }

    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy([], null, $limit, $offset);
    }

    public function delete(Ingredient $ingredient): void
    {
        $this->getEntityManager()->remove($ingredient);
        $this->getEntityManager()->flush();
    }
}
