<?php
namespace App\ShoppingList\Application\Query\ShoppingListInstance;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ShoppingListInstanceQueryHandler
{
    /**
     * @param ShoppingListRepositoryInterface $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private ShoppingListRepositoryInterface $repository,
        private LoggerInterface $logger
    )
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
            $items = [];
            foreach ($shoppingList->items() as $item)
            {
                $items[] = new ShoppingListItemDto(
                    id: $item->getId()->toString(),
                    ingredientId: $item->getIngredientReference()->value()->toString(),
                    unitOfMeasureId: $item->getUnitOfMeasure()->value()->toString(),
                    quantity: $item->getQuantity()->value(),
                    createdAt: $item->getCreatedAt(),
                    updatedAt: $item->getUpdatedAt(),
                );
            }
            return new ShoppingListInstanceResponse(new ShoppingListDto(
                id: $shoppingList->getId()->toString(),
                name: $shoppingList->getName()->value(),
                items: $items,
                scheduledFor: $shoppingList->getScheduledFor(),
                createdAt: $shoppingList->getCreatedAt(),
                updatedAt: $shoppingList->getUpdatedAt(),
            ));
        }
        else
        {
            $e = new ShoppingListNotFoundException($id);
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
