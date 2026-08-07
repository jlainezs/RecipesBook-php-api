<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\ShoppingList\Application\Query\SoppingListsCollection\ShoppingListsCollectionQuery;
use App\ShoppingList\Application\Query\SoppingListsCollection\ShoppingListsQueryResponse;
use App\ShoppingList\Presentation\Http\Controller\ShoppingListsCollectionController;
use App\ShoppingList\Presentation\Http\Response\ShoppingListsCollectionJsonResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class ShoppingListsCollectionControllerTest extends TestCase
{
    #[Test]
    public function test_it_validates_dispatches_command_and_returns_201_response(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);

        $queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function(ShoppingListsCollectionQuery $query){
                    return $query->offset >= 0
                        && $query->limit > 0;
                }))
            ->willReturn(new ShoppingListsQueryResponse([]));
        $controller = new ShoppingListsCollectionController($queryBus, $validator);
        $request = Request::create(
            uri: '/shopping-lists?offset=0&limit=10',
            server: ['CONTENT_TYPE' => 'application/json']
        );

        $response = $controller($request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
