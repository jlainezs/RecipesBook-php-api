<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\ShoppingList\Application\Query\ShoppingListInstance\ShoppingListInstanceQuery;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Presentation\Http\Controller\ShoppingListsInstance;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShoppingListsInstanceTest extends TestCase
{
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $shoppingList = ShoppingList::create('Shopping List');

        $queryBus->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn(ShoppingListInstanceQuery $query) => $query->id === $shoppingList->getId()->toString()
            ))
            ->willReturn($shoppingList);
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn(ShoppingListInstanceQuery $query) => $query->id === $shoppingList->getId()->toString()
            ));
        $controller = new ShoppingListsInstance($queryBus, $validator);
        $response = $controller($shoppingList->getId()->toString());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
