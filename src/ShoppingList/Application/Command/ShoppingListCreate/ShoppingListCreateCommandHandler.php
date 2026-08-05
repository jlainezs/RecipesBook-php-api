<?php
namespace App\ShoppingList\Application\Command\ShoppingListCreate;

use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ShoppingListCreateCommandHandler
{
    public function __construct(private ShoppingListRepositoryInterface $repository)
    {}

    public function __invoke(ShoppingListCreateCommand $command): void
    {
        $shoppingList = ShoppingList::create($command->name);
        $this->repository->save($shoppingList);
    }
}
