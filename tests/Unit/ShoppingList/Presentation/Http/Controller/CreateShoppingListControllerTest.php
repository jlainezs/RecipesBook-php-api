<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListCommand;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListCommandFactory;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListDto;
use App\ShoppingList\Presentation\Http\Controller\CreateShoppingListController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CreateShoppingListControllerTest extends TestCase
{
    #[Test]
    public function test_it_validates_dispatches_command_and_returns_201_response(): void
    {
        // 1. Arrange
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory = $this->createMock(CreateShoppingListCommandFactory::class);
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $request = new CreateShoppingListDto('Llista Setmanal', []);

        // Comprovem que el validador rep la comanda amb el nom correcte
        $cmdFactory->expects($this->once())
            ->method('createFromDto')
            ->with($request)
            ->willReturn(new CreateShoppingListCommand(
                AggregateRootId::generateId()->toString(),
                'Llista Setmanal',
                [])
            );

        // Comprovem que el CommandBus rep exactament la mateixa comanda
        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (CreateShoppingListCommand $cmd) => $cmd->name === $request->name)
            );

        $urlGenerator->expects($this->once())
            ->method('generate')
            ->withAnyParameters();

        $controller = new CreateShoppingListController($commandBus, $cmdFactory, $urlGenerator);

        // 2. Act
        $response = $controller($request);

        // 3. Assert
        // $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(201, $response->getStatusCode());
    }
}
