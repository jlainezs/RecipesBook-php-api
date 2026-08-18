<?php
namespace App\ShoppingList\Application\Command\ShoppingListCreate;

use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\IngredientReference;
use App\Shared\Domain\ValueObject\UnitOfMeasureReference;
use App\ShoppingList\Domain\Exceptions\InvalidIngredient;
use App\ShoppingList\Domain\Exceptions\InvalidUnitOfMeasure;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Domain\Model\ShoppingListItem;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use App\ShoppingList\Domain\ValueObjects\ShoppingListItemQuantity;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ShoppingListCreateCommandHandler
{
    public function __construct(
        private ShoppingListRepositoryInterface $repository,
        private IngredientRepositoryInterface $ingredientRepository,
        private UnitOfMeasureRepositoryInterface $unitOfMeasureRepository
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(ShoppingListCreateCommand $command): void
    {
        $shoppingList = ShoppingList::create($command->name, []);

        foreach ($command->items as $reqItem)
        {
            $ingredientId = new AggregateRootId($reqItem['ingredientId']);
            if (!($ingredient = $this->ingredientRepository->findOne($ingredientId)))
            {
                throw new InvalidIngredient($ingredientId);
            }

            $uomId = new AggregateRootId($reqItem['unitOfMeasureId']);
            if (!($unitOfMeasure = $this->unitOfMeasureRepository->findOne($uomId)))
            {
                throw new InvalidUnitOfMeasure($uomId);
            }

            $item = ShoppingListItem::create(
                $shoppingList,
                new IngredientReference($ingredient->getId()->toString()),
                new UnitOfMeasureReference($unitOfMeasure->getId()->toString()),
                new ShoppingListItemQuantity($reqItem['quantity'])
            );
            $shoppingList->addItem($item);
        }

        $this->repository->save($shoppingList);
    }
}
