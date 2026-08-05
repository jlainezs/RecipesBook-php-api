<?php
namespace App\ShoppingList\Infrastructure\Repository;

use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ShoppingListRepository extends ServiceEntityRepository implements ShoppingListRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingList::class);
    }

    public function findOne(AggregateRootId $id): ?ShoppingList
    {
        return parent::find($id);
    }

    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy([], null, $limit, $offset);
    }

    public function save(ShoppingList $shoppingList): void
    {
        $this->getEntityManager()->persist($shoppingList);
        $this->getEntityManager()->flush();
    }

    public function delete(ShoppingList $shoppingList): void
    {
        $this->getEntityManager()->remove($shoppingList);
        $this->getEntityManager()->flush();
    }
}
