<?php
namespace App\ShoppingList\Domain\Repository;

use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Domain\Model\ShoppingList;

interface ShoppingListRepositoryInterface
{
    public function findOne(AggregateRootId $id): ?ShoppingList;

    public function findAll(int|null $limit = null, int|null $offset = null): array;

    public function save(ShoppingList $shoppingList): void;

    public function delete(ShoppingList $shoppingList): void;
}
