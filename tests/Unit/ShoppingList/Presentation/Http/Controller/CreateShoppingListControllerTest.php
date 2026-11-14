<?php
namespace App\Tests\Unit\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListCommand;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListCommandFactory;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListDto;
use App\ShoppingList\Presentation\Http\Controller\CreateShoppingListController;
use App\ShoppingList\Presentation\Http\Resource\ShoppingListResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CreateShoppingListControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function test_it_validates_dispatches_command_and_returns_201_response(): void
    {
        // 1. Arrange
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory = $this->createMock(CreateShoppingListCommandFactory::class);
        $resource = new ShoppingListResource($this->createStub(UrlGeneratorInterface::class));

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

        $controller = new CreateShoppingListController($commandBus, $cmdFactory, $resource);

        // 2. Act
        $response = $controller($request);
        $document = json_decode($response->getContent());

        // 3. Assert
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertSame('application/vnd.api+json', $response->headers->get('Content-Type'));
        $this->assertIsObject($response);
        $this->assertObjectHasProperty('jsonapi', $document);
        $this->assertObjectHasProperty('data', $document);
        $this->assertObjectHasProperty('links', $document);
        $this->assertObjectHasProperty('attributes', $document->data);
        $this->assertObjectHasProperty('id', $document->data);
        $this->assertObjectHasProperty('type', $document->data);
        $this->assertObjectHasProperty('links', $document->data);
        $this->assertObjectHasProperty('id', $document->data->attributes);
    }
}
