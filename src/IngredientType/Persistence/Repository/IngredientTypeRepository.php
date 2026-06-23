<?php
namespace App\IngredientType\Persistence\Repository;

use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Domain\Persistence\IngredientTypeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

class IngredientTypeRepository extends ServiceEntityRepository implements IngredientTypeRepositoryInterface
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

    public function find(mixed $id,  LockMode|int|null $lockMode = null, int|null $lockVersion = null): ?IngredientType
    {
        return parent::find($id, $lockMode, $lockVersion);
    }
}
