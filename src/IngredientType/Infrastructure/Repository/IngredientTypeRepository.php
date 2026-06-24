<?php
namespace App\IngredientType\Infrastructure\Repository;

use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class IngredientTypeRepository extends ServiceEntityRepository implements IngredientTypeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientType::class);
    }

    public function save(IngredientType $ingredientType): void
    {
        $this->getEntityManager()->persist($ingredientType);
        $this->getEntityManager()->flush();
    }

    public function findOne(string $id): ?IngredientType
    {
        return parent::find($id);
    }

    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy([], null, $limit, $offset);
    }
}
