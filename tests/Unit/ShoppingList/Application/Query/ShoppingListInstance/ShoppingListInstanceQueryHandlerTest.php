<?php
namespace App\Tests\Unit\ShoppingList\Application\Query\ShoppingListInstance;

use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Application\Query\ShoppingListInstance\ShoppingListInstanceQuery;
use App\ShoppingList\Application\Query\ShoppingListInstance\ShoppingListInstanceQueryHandler;
use App\ShoppingList\Application\Query\ShoppingListInstance\ShoppingListInstanceResponse;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Infrastructure\Repository\ShoppingListRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShoppingListInstanceQueryHandlerTest extends TestCase
{
    private ShoppingListInstanceQueryHandler $handler;
    private ShoppingListRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ShoppingListRepository::class);
        $this->handler = new ShoppingListInstanceQueryHandler($this->repository);
    }

    /**
     * @throws ShoppingListNotFoundException
     */
    #[Test]
    public function it_should_return_the_shopping_list(): void
    {
        $shoppingList = ShoppingList::create('test');
        $id = $shoppingList->getId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($shoppingList);
        $queryResult = $this->handler->__invoke(new ShoppingListInstanceQuery($id));
        $this->assertNotNull($queryResult);
        $this->assertInstanceOf(ShoppingListInstanceResponse::class, $queryResult);
        $this->assertEquals($id->toString(), $queryResult->shoppingListDto->id);
    }

    #[Test]
    public function it_should_throw_when_shopping_list_not_found(): void
    {
        $id = AggregateRootId::generateId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);
        $this->expectException(ShoppingListNotFoundException::class);
        ($this->handler)(new ShoppingListInstanceQuery($id));
    }
}
