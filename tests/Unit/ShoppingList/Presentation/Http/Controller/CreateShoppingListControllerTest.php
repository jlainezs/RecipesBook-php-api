<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\ShoppingList\Application\Command\ShoppingListCreate\ShoppingListCreateCommand;
use App\ShoppingList\Application\Command\ShoppingListCreate\ShoppingListCreateDto;
use App\ShoppingList\Presentation\Http\Controller\CreateShoppingListController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class CreateShoppingListControllerTest extends TestCase
{
    #[Test]
    public function test_it_validates_dispatches_command_and_returns_201_response(): void
    {
        // 1. Arrange
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);

        $listName = 'Llista Setmanal';

        // Comprovem que el validador rep la comanda amb el nom correcte
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(function (ShoppingListCreateCommand $cmd) use ($listName) {
                return $cmd->name === $listName;
            }));

        // Comprovem que el CommandBus rep exactament la mateixa comanda
        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (ShoppingListCreateCommand $cmd) use ($listName) {
                return $cmd->name === $listName;
            }));

        $controller = new CreateShoppingListController($commandBus, $validator);
        $request = new ShoppingListCreateDto($listName);

        // 2. Act
        $response = $controller($request);

        // 3. Assert
        // $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(201, $response->getStatusCode());
    }
}
