<?php
namespace App\Tests\Unit\ShoppingList\Application\Command\ShoppingListDelete;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\ShoppingList\Application\Command\ShoppingListDelete\ShoppingListDeleteCommand;
use App\ShoppingList\Application\Command\ShoppingListDelete\ShoppingListDeleteCommandHandler;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShoppingListDeleteCommandHandlerTest extends TestCase
{
    private ShoppingListRepositoryInterface $repository;
    private ShoppingListDeleteCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ShoppingListRepositoryInterface::class);
        $this->handler = new ShoppingListDeleteCommandHandler($this->repository);
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ShoppingListNotFoundException
     */
    #[Test]
    public function it_deletes_the_shopping_list(): void
    {
        $shoppingList = ShoppingList::create('shopping list');
        $id = $shoppingList->getId()->toString();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($shoppingList);

        $this->repository
            ->expects($this->once())
            ->method('delete')
            ->with($shoppingList);

        $command = new ShoppingListDeleteCommand($id);
        ($this->handler)(new ShoppingListDeleteCommand($id));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_the_shopping_list_is_not_found(): void
    {
        $shoppingList = ShoppingList::create('shopping list');
        $id = $shoppingList->getId()->toString();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);
        $this->repository
            ->expects($this->never())
            ->method('delete');
        $this->expectException(ShoppingListNotFoundException::class);
        ($this->handler)(new ShoppingListDeleteCommand($id));
    }
}
