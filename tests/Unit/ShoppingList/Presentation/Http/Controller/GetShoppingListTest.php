<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Application\Query\GetShoppingList\GetShoppingListQuery;
use App\ShoppingList\Application\Query\GetShoppingList\GetShoppingListQueryFactory;
use App\ShoppingList\Application\Query\GetShoppingList\GetShoppingListQueryRouteDto;
use App\ShoppingList\Application\Query\GetShoppingList\ShoppingListResponse;
use App\ShoppingList\Application\Query\ShoppingListDto;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Presentation\Http\Controller\GetShoppingListController;
use App\ShoppingList\Presentation\Http\Resource\ShoppingListResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $resource = new ShoppingListResource($this->createStub(UrlGeneratorInterface::class));
        $shoppingList = ShoppingList::create(AggregateRootId::generateId()->toString(), 'Shopping List', []);
        $query =  new GetShoppingListQuery($shoppingList->getId()->toString());
        $request = new GetShoppingListQueryRouteDto($shoppingList->getId()->toString());

        $queryFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn($query);
        $queryBus->expects($this->once())
            ->method('ask')
            ->with($query)
            ->willReturn(new ShoppingListResponse(new ShoppingListDto(
                $shoppingList->getId()->toString(),
                $shoppingList->getName()->value(),
                (array)$shoppingList->getItems(),
                $shoppingList->getScheduledFor(),
                $shoppingList->getCreatedAt(),
                $shoppingList->getUpdatedAt()
            )));
        $controller = new GetShoppingListController($queryBus, $queryFactory, $resource);
        $response = $controller($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
