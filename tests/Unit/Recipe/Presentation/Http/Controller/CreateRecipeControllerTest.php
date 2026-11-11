<?php
namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\CreateRecipe\CreateRecipeCommand;
use App\Recipe\Application\Command\CreateRecipe\CreateRecipeCommandFactory;
use App\Recipe\Application\Command\CreateRecipe\CreateRecipeDto;
use App\Recipe\Presentation\Http\Controller\CreateRecipeController;
use App\Recipe\Presentation\Http\Resource\RecipeResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateRecipeControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory =$this->createMock(CreateRecipeCommandFactory::class);
        $resource = new RecipeResource($this->createStub(UrlGeneratorInterface::class));
        $id = AggregateRootId::generateId();
        $request = new CreateRecipeDto(
            name: 'test',
            servings: 4,
            rating: 5,
            description: 'test',
            source: 'test',
            steps: [],
            ingredients: []
        );
        $cmdFactory->expects($this->once())
            ->method('createFromDto')
            ->with($request)
            ->willReturn(new CreateRecipeCommand(
                id: $id->toString(),
                name: $request->name,
                servings: $request->servings,
                rating: $request->rating,
                description: $request->description,
                source: $request->source,
                steps: $request->steps,
                ingredients: $request->ingredients
            ));

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (CreateRecipeCommand $cmd) => $cmd->name === $request->name
            ));

        $controller = new CreateRecipeController($commandBus, $cmdFactory, $resource);

        $response = $controller($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
