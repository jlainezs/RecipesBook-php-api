<?php
namespace App\ShoppingList\Application\Command\ShoppingListUpdate;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ShoppingListUpdateCommandHandler
{
    public function __construct(
        private ShoppingListRepositoryInterface $repository
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ShoppingListNotFoundException
     */
    public function __invoke(ShoppingListUpdateCommand $command):void
    {
        $id = new AggregateRootId($command->id);
        if ($shoppingList = $this->repository->findOne($id))
        {
            $shoppingList->rename($command->name);
            $this->repository->save($shoppingList);
        }
        else
        {
            throw new ShoppingListNotFoundException($id);
        }
    }
}
