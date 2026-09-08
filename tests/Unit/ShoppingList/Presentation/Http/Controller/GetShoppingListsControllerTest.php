<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\ShoppingList\Application\Query\SoppingLists\ShoppingListsDto;
use App\ShoppingList\Application\Query\SoppingLists\ShoppingListsQuery;
use App\ShoppingList\Application\Query\SoppingLists\ShoppingListsQueryResponse;
use App\ShoppingList\Presentation\Http\Controller\GetShoppingListsController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class GetShoppingListsControllerTest extends TestCase
{
    #[Test]
    public function test_it_validates_dispatches_command_and_returns_200_response(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);

        $queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function(ShoppingListsQuery $query){
                    return $query->offset >= 0
                        && $query->limit > 0;
                }))
            ->willReturn(new ShoppingListsQueryResponse([]));
        $validator->expects($this->once())
            ->method('validate')
            ->withAnyParameters();

        $controller = new GetShoppingListsController($queryBus, $validator);
        $request = new ShoppingListsDto(
            offset: 0,
            limit: 10
        );

        $response = $controller($request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
