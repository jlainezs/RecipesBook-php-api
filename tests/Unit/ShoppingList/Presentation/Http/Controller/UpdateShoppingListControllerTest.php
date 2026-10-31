<?php

namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Application\Command\ShoppingListUpdate\UpdateShoppingListCommand;
use App\ShoppingList\Application\Command\ShoppingListUpdate\UpdateShoppingListCommandFactory;
use App\ShoppingList\Application\Command\ShoppingListUpdate\UpdateShoppingListDto;
use App\ShoppingList\Application\Command\ShoppingListUpdate\UpdateShoppingListRouteDto;
use App\ShoppingList\Presentation\Http\Controller\UpdateShoppingListController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class UpdateShoppingListControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory = $this->createMock(UpdateShoppingListCommandFactory::class);
        $logger = $this->createMock(LoggerInterface::class);
        $id = AggregateRootId::generateId();
        $list = new UpdateShoppingListDto('Shopping List', []);
        $routeRequest = new UpdateShoppingListRouteDto($id->toString());

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn(UpdateShoppingListCommand $cmd) => $cmd->id === $id->toString()
            ));
        $cmdFactory->expects($this->once())
            ->method('createFromRequest')
            ->withAnyParameters()
            ->willReturn(new UpdateShoppingListCommand($routeRequest->id, $list->name, $list->items));
        $logger->expects($this->never())
            ->method('error')
            ->withAnyParameters();
        $controller = new UpdateShoppingListController($commandBus, $cmdFactory, $logger);
        $response = $controller($routeRequest, $list);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
