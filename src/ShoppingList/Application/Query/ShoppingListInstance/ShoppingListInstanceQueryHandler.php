<?php
namespace App\ShoppingList\Application\Query\ShoppingListInstance;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ShoppingListInstanceQueryHandler
{
    /**
     * @param ShoppingListRepositoryInterface $repository
     */
    public function __construct(private ShoppingListRepositoryInterface $repository)
    {}

    /**
     * @param ShoppingListInstanceQuery $query
     * @return ShoppingListInstanceResponse
     * @throws EmptyIdNotAllowedException
     * @throws ShoppingListNotFoundException
     */
    public function __invoke(ShoppingListInstanceQuery $query): ShoppingListInstanceResponse
    {
        $id = new AggregateRootId($query->id);
        if ($shoppingList = $this->repository->findOne($id))
        {
            return new ShoppingListInstanceResponse(new ShoppingListDto(
                id: $shoppingList->getId()->toString(),
                name: $shoppingList->getName()->value(),
                items: $shoppingList->getItems(),
                scheduledFor: $shoppingList->getScheduledFor(),
                createdAt: $shoppingList->getCreatedAt(),
                updatedAt: $shoppingList->getUpdatedAt(),
            ));
        }
        else
        {
            throw new ShoppingListNotFoundException($id);
        }
    }
}
