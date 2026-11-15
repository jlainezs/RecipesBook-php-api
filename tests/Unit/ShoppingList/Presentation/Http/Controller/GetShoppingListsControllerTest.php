<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Query\PaginatedList\PageDto;
use App\ShoppingList\Application\Query\GetSoppingLists\GetShoppingListsQueryDto;
use App\ShoppingList\Application\Query\GetSoppingLists\GetShoppingListsQuery;
use App\ShoppingList\Application\Query\GetSoppingLists\GetShoppingListsQueryFactory;
use App\ShoppingList\Application\Query\GetSoppingLists\GetShoppingListsQueryResponse;
use App\ShoppingList\Presentation\Http\Controller\GetShoppingListsController;
use App\ShoppingList\Presentation\Http\Resource\ShoppingListResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetShoppingListsControllerTest extends TestCase
{
    #[Test]
    public function test_it_validates_dispatches_command_and_returns_200_response(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $queryFactory = $this->createMock(GetShoppingListsQueryFactory::class);
        $resource = new ShoppingListResource($this->createStub(UrlGeneratorInterface::class));

        $request = new GetShoppingListsQueryDto(
            new PageDto(
                offset: 0,
                limit: 10
            )
        );

        $queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function(GetShoppingListsQuery $query){
                    return $query->offset >= 0
                        && $query->limit > 0;
                }))
            ->willReturn(new GetShoppingListsQueryResponse([]));
        $queryFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new GetShoppingListsQuery(
                $request->page->offset, $request->page->limit
            ));

        $controller = new GetShoppingListsController($queryBus, $queryFactory, $resource);

        $response = $controller($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
