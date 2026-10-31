<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Application\Command\ShoppingListDelete\DeleteShoppingListCommand;
use App\ShoppingList\Application\Command\ShoppingListDelete\DeleteShoppingListCommandFactory;
use App\ShoppingList\Application\Command\ShoppingListDelete\DeleteShoppingListRouteDto;
use App\ShoppingList\Presentation\Http\Controller\DeleteShoppingListController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteShoppingListControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function test_it_validates_dispatches_command_and_returns_204_response(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $commandFactory = $this->createMock(DeleteShoppingListCommandFactory::class);
        $id = AggregateRootId::generateId()->toString();
        $request = new DeleteShoppingListRouteDto($id);

        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();
        $commandFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(
                new DeleteShoppingListCommand($id)
            );

        $controller = new DeleteShoppingListController($commandBus, $commandFactory);
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
