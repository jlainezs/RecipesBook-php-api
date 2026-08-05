<?php

namespace App\ShoppingList\Application\Command\ShoppingListDelete;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ShoppingListDeleteCommandHandler
{
    public function __construct(
        private ShoppingListRepositoryInterface $repository
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ShoppingListNotFoundException
     */
    public function __invoke(ShoppingListDeleteCommand $command): void
    {
        $id = new AggregateRootId($command->id);
        $shoppingList = $this->repository->findOne($id);

        if ($shoppingList)
        {
            $this->repository->delete($shoppingList);
        }
        else
        {
            throw new ShoppingListNotFoundException($id);
        }
    }
}
