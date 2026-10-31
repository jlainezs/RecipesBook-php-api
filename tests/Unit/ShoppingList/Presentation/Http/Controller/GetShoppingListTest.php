<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Application\Query\GetShoppingList\GetShoppingListQuery;
use App\ShoppingList\Application\Query\GetShoppingList\GetShoppingListQueryFactory;
use App\ShoppingList\Application\Query\GetShoppingList\GetShoppingListQueryRouteDto;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Presentation\Http\Controller\GetShoppingList;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class GetShoppingListTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $queryFactory = $this->createMock(GetShoppingListQueryFactory::class);
        $shoppingList = ShoppingList::create(AggregateRootId::generateId()->toString(), 'Shopping List', []);
        $request = new GetShoppingListQueryRouteDto($shoppingList->getId()->toString());

        $queryBus->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn(GetShoppingListQuery $query) => $query->id === $shoppingList->getId()->toString()
            ))
            ->willReturn($shoppingList);
        $queryFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(
                new GetShoppingListQuery($shoppingList->getId()->toString())
            );
        $controller = new GetShoppingList($queryBus, $queryFactory);
        $response = $controller($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
