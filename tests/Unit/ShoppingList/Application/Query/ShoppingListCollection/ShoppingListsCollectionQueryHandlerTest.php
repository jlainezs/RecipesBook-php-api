<?php
namespace App\Tests\Unit\ShoppingList\Application\Query\ShoppingListCollection;

use App\ShoppingList\Application\Query\SoppingListsCollection\ShoppingListsCollectionQuery;
use App\ShoppingList\Application\Query\SoppingListsCollection\ShoppingListsCollectionQueryHandler;
use App\ShoppingList\Application\Service\ShoppingListItemsPager;
use App\ShoppingList\Domain\Model\ShoppingList;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShoppingListsCollectionQueryHandlerTest extends TestCase
{
    private ShoppingListItemsPager $pager;
    private ShoppingListsCollectionQueryHandler $handler;

    protected function setUp(): void
    {
        $this->pager = $this->createMock(ShoppingListItemsPager::class);
        $this->handler = new ShoppingListsCollectionQueryHandler($this->pager);
    }

    #[Test]
    public function it_returns_a_response_with_mapped_dto(): void
    {
        $sl1 = ShoppingList::create('list 1');
        $sl2 = ShoppingList::create('list 2');
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(0, 20)
            ->willReturn([$sl1, $sl2]);

        $response = ($this->handler)(new ShoppingListsCollectionQuery(0, 20));

        $this->assertCount(2, $response->items);
        $this->assertSame($sl1->getId()->toString(), $response->items[0]->id);
        $this->assertSame($sl2->getId()->toString(), $response->items[1]->id);
        $this->assertSame("list 1", $sl1->getName()->value());
        $this->assertSame("list 2", $sl2->getName()->value());
    }

    #[Test]
    public function it_returns_an_empty_response_when_no_items_exist(): void
    {
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->willReturn([]);
        $response = ($this->handler)(new ShoppingListsCollectionQuery(0, 20));
        $this->assertCount(0, $response->items);
    }

    #[Test]
    public function it_forwards_offset_and_limit_to_the_pager(): void
    {
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(10, 5)
            ->willReturn([]);
        ($this->handler)(new ShoppingListsCollectionQuery(10, 5));
    }
}
