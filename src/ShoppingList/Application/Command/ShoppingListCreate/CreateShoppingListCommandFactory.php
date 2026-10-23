<?php
namespace App\ShoppingList\Application\Command\ShoppingListCreate;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;

readonly class CreateShoppingListCommandFactory
{
    public function __construct()
    {}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function createFromDto(CreateShoppingListDto $dto): CreateShoppingListCommand
    {
        return new CreateShoppingListCommand(
            AggregateRootId::generateId()->toString(),
            $dto->name,
            $dto->items
        );
    }
}
