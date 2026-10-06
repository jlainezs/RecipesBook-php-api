<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeCommand;
use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeCommandFactory;
use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeDto;
use App\IngredientType\Presentation\Http\Controller\CreateIngredientTypeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateIngredientTypeControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $createCmdFactory = $this->createMock(CreateIngredientTypeCommandFactory::class);
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $id = AggregateRootId::generateId()->toString();
        $ingredientTypeName = 'test name';
        $request = new CreateIngredientTypeDto(
            name: $ingredientTypeName
        );

        $createCmdFactory->expects($this->once())
            ->method('createFromDto')
            ->with($request)
            ->willReturn(new CreateIngredientTypeCommand(
                $id, $request->name
            )
        );

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function(CreateIngredientTypeCommand $cmd) use ($id, $ingredientTypeName) {
                    return (($id === $cmd->id) && ($cmd->name === $ingredientTypeName));
                }
            ));

        $urlGenerator->expects($this->once())
            ->method('generate')
            ->withAnyParameters();

        $controller = new CreateIngredientTypeController($commandBus, $createCmdFactory, $urlGenerator);
        $response = $controller($request);

        $this->assertEquals(201, $response->getStatusCode());
    }
}
