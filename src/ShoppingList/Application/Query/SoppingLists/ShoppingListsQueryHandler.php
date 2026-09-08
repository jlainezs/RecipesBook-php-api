<?php
namespace App\ShoppingList\Application\Query\SoppingLists;

use App\ShoppingList\Application\Query\ShoppingList\ShoppingListDto;
use App\ShoppingList\Application\Service\ShoppingListItemsPager;
use App\ShoppingList\Domain\Model\ShoppingList;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ShoppingListsQueryHandler
{
    public function __construct(private ShoppingListItemsPager $list)
    {}

    public function __invoke(ShoppingListsQuery $query): ShoppingListsQueryResponse
    {
        $itemsDto = array_map(
            fn(ShoppingList $item) => new ShoppingListDto(
                id: $item->getId()->toString(),
                name: $item->getName()->value(),
                items: $item->itemsAsArray(),
                scheduledFor: $item->getScheduledFor(),
                createdAt: $item->getCreatedAt(),
                updatedAt: $item->getUpdatedAt(),
            ),
            $this->list->items($query->offset, $query->limit)
        );

        return new ShoppingListsQueryResponse($itemsDto);
    }
}
