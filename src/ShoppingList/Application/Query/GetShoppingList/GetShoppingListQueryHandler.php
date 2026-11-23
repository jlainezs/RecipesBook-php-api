<?php
namespace App\ShoppingList\Application\Query\GetShoppingList;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Application\Query\ShoppingListDto;
use App\ShoppingList\Application\Query\ShoppingListItemDto;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetShoppingListQueryHandler
{
    /**
     * @param ShoppingListRepositoryInterface $repository
     */
    public function __construct(
        private ShoppingListRepositoryInterface $repository
    )
    {}

    /**
     * @param GetShoppingListQuery $query
     * @return ShoppingListResponse
     * @throws EmptyIdNotAllowedException
     * @throws ShoppingListNotFoundException
     */
    public function __invoke(GetShoppingListQuery $query): ShoppingListResponse
    {
        $id = new AggregateRootId($query->id);
        if ($shoppingList = $this->repository->findOne($id))
        {

            return new ShoppingListResponse(ShoppingListDto::fromDomain($shoppingList));
        }
        else
        {
            throw new ShoppingListNotFoundException($id);
        }
    }
}
