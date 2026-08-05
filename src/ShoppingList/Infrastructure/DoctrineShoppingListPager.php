<?php
namespace App\ShoppingList\Infrastructure;

use App\ShoppingList\Application\Service\ShoppingListItemsPager;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;

final readonly class DoctrineShoppingListPager implements ShoppingListItemsPager
{
    public function __construct(private ShoppingListRepositoryInterface $repository)
    {}

    public function items(int $offset = 0, int $limit = 20): array
    {
        return $this->repository->findAll($limit, $offset);
    }
}
