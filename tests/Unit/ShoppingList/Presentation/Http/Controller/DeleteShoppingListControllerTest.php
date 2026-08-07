<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Application\Command\ShoppingListDelete\ShoppingListDeleteCommand;
use App\ShoppingList\Presentation\Http\Controller\DeleteShoppingListController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class DeleteShoppingListControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function test_it_validates_dispatches_command_and_returns_204_response(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);

        $id = AggregateRootId::generateId()->toString();

        $validator
            ->expects($this->never())
            ->method('validate');

        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (ShoppingListDeleteCommand $cmd) use ($id) {
                return $cmd->id === $id;
            }));

        $controller = new DeleteShoppingListController($commandBus, $validator);
        $request = $request = Request::create(
            uri: '/api/v1/shopping-lists/' . $id,
            method: 'DELETE',
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $response = $controller($request);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
