<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
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

        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();
        $validator->expects($this->once())
            ->method('validate')
            ->withAnyParameters();

        $controller = new DeleteShoppingListController($commandBus, $validator);
        $request = Request::create(
            uri: '/api/v1/shopping-lists/' . $id,
            method: 'DELETE',
            server: ['CONTENT_TYPE' => 'application/json'],
        );

        $response = $controller($request);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
