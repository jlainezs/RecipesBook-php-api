<?php
namespace App\Tests\Unit\ShoppingList\Application\Command\ShoppingListUpdate;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Application\Command\ShoppingListUpdate\ShoppingListUpdateCommand;
use App\ShoppingList\Application\Command\ShoppingListUpdate\ShoppingListUpdateCommandHandler;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Infrastructure\Repository\ShoppingListRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShoppingListUpdateCommandHandlerTest extends TestCase
{
    private ShoppingListUpdateCommandHandler $handler;
    private ShoppingListRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ShoppingListRepository::class);
        $this->handler = new ShoppingListUpdateCommandHandler($this->repository);
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ShoppingListNotFoundException
     */
    #[Test]
    public function it_should_update_shopping_list():void
    {
        $shoppingList = ShoppingList::create('test');
        $id = $shoppingList->getId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($shoppingList);
        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($shoppingList);
        ($this->handler)(new ShoppingListUpdateCommand($id, 'new name'));

        $this->assertSame('new name', $shoppingList->getName()->value());
    }

    /**
     * @throws ShoppingListNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_setting_empty_name(): void
    {
        $shoppingList = ShoppingList::create('test');
        $id = $shoppingList->getId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($shoppingList);
        $this->repository
            ->expects($this->never())
            ->method('save');
        $this->expectException(EmptyRequiredNameException::class);
        ($this->handler)(new ShoppingListUpdateCommand($id, ''));
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ShoppingListNotFoundException
     */
    #[Test]
    public function it_throws_when_shopping_list_is_not_found(): void
    {
        $id = AggregateRootId::generateId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);
        $this->repository
            ->expects($this->never())
            ->method('save');
        $this->expectException(ShoppingListNotFoundException::class);
        ($this->handler)(new ShoppingListUpdateCommand($id, 'new name'));
    }
}
