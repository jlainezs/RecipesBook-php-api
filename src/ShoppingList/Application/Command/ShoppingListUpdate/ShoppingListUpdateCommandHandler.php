<?php
namespace App\ShoppingList\Application\Command\ShoppingListUpdate;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Shared\Domain\ValueObjects\IngredientReference;
use App\Shared\Domain\ValueObjects\UnitOfMeasureReference;
use App\ShoppingList\Domain\Exceptions\ShoppingListItemNotFoundException;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use App\ShoppingList\Domain\Model\ShoppingListItem;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use App\ShoppingList\Domain\ValueObjects\ShoppingListItemQuantity;
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
     * @throws ShoppingListItemNotFoundException
     */
    public function __invoke(ShoppingListUpdateCommand $command):void
    {
        $id = new AggregateRootId($command->id);
        $items = [];

        if ($shoppingList = $this->repository->findOne($id))
        {
            $notProcessedItems = $shoppingList->itemsAsArray();

            foreach ($command->items as $item)
            {
                if (!empty($item['id']))
                {
                    $itemId = new AggregateRootId($item['id']);
                    if ($itemObject = $shoppingList->getItem($itemId))
                    {
                        $quantityRaw = $item['quantity'];
                        $newQuantity = new ShoppingListItemQuantity($quantityRaw);
                        $itemObject->changeQuantity($newQuantity);

                        if (isset($notProcessedItems[$itemId->toString()]))
                        {
                            unset($notProcessedItems[$itemId->toString()]);
                        }
                    }
                    else
                    {
                        throw new ShoppingListItemNotFoundException($item['id']);
                    }
                }
                else
                {
                    $itemObject = ShoppingListItem::create(
                        $shoppingList,
                        new IngredientReference($item['ingredientId']),
                        new UnitOfMeasureReference($item['unitOfMeasureId']),
                        new ShoppingListItemQuantity($item['quantity'])
                    );
                    $shoppingList->addItem($itemObject);
                }

                $items[] = $itemObject;
            }

            $shoppingList->setItems($items);
            $shoppingList->rename($command->name);
            $this->repository->save($shoppingList);
        }
        else
        {
            throw new ShoppingListNotFoundException($id);
        }
    }
}
