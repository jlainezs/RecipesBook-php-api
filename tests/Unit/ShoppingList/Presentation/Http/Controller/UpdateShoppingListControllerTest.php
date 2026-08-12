<?php

namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Application\Command\ShoppingListUpdate\ShoppingListUpdateCommand;
use App\ShoppingList\Application\Command\ShoppingListUpdate\ShoppingListUpdateDto;
use App\ShoppingList\Presentation\Http\Controller\UpdateShoppingListController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class UpdateShoppingListControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $logger = $this->createMock(LoggerInterface::class);
        $id = AggregateRootId::generateId();
        $list = new ShoppingListUpdateDto('Shopping List');

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn(ShoppingListUpdateCommand $cmd) => $cmd->id === $id->toString()
            ));
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn(ShoppingListUpdateCommand $cmd) => $cmd->id === $id->toString()
            ));
        $controller = new UpdateShoppingListController($commandBus, $validator, $logger);

        $response = $controller($id->toString(), $list);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
