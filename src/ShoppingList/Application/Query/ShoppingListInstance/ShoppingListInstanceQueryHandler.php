<?php
namespace App\ShoppingList\Application\Query\ShoppingListInstance;

use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use App\ShoppingList\Infrastructure\Repository\ShoppingListRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ShoppingListInstanceQueryHandler
{
    public function __construct(private ShoppingListRepository $repository)
    {}

    public function __invoke(ShoppingListInstanceQuery $query)
    {
        $id = new AggregateRootId($query->id);
        if ($shoppingList = $this->repository->findOne($id))
        {
            return new ShoppingListInstanceResponse(new ShoppingListDto(
                id: $shoppingList->getId()->toString(),
                name: $shoppingList->getName()->value(),
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
