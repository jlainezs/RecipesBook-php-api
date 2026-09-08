<?php
namespace App\Tests\Unit\ShoppingList\Application\Query\ShoppingList;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Application\Query\ShoppingList\ShoppingListQuery;
use App\ShoppingList\Application\Query\ShoppingList\ShoppingListQueryHandler;
use App\ShoppingList\Application\Query\ShoppingList\ShoppingListResponse;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use App\ShoppingList\Infrastructure\Repository\ShoppingListRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ShoppingListQueryHandlerTest extends TestCase
{
    private ShoppingListQueryHandler $handler;
    private LoggerInterface $logger;
    private ShoppingListRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ShoppingListRepository::class);
        $this->logger = $this->createStub(LoggerInterface::class);
        $this->handler = new ShoppingListQueryHandler($this->repository, $this->logger);
    }

    /**
     * @throws ShoppingListNotFoundException
     */
    #[Test]
    public function it_should_return_the_shopping_list(): void
    {
        $shoppingList = ShoppingList::create('test', []);
        $id = $shoppingList->getId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($shoppingList);
        $queryResult = $this->handler->__invoke(new ShoppingListQuery($id));
        $this->assertNotNull($queryResult);
        $this->assertInstanceOf(ShoppingListResponse::class, $queryResult);
        $this->assertEquals($id->toString(), $queryResult->shoppingListDto->id);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
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
        ($this->handler)(new ShoppingListQuery($id));
    }
}
