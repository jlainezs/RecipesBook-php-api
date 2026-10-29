<?php
namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\RecipeUpdate\UpdateRecipeCommand;
use App\Recipe\Application\Command\RecipeUpdate\UpdateRecipeCommandFactory;
use App\Recipe\Application\Command\RecipeUpdate\UpdateRecipeDto;
use App\Recipe\Application\Command\RecipeUpdate\UpdateRecipeRouteDto;
use App\Recipe\Presentation\Http\Controller\UpdateRecipeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class UpdateRecipeControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private UpdateRecipeCommandFactory $cmdFactory;
    private LoggerInterface $logger;

    public function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->cmdFactory = $this->createMock(UpdateRecipeCommandFactory::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_update_ingredient_type(): void
    {
        $id = AggregateRootId::generateId();
        $recipe = new UpdateRecipeDto(
            name: 'test',
            servings: 4,
            rating: 5,
            description: 'test',
            source: 'test',
            steps: [],
            ingredients: []
        );
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn(UpdateRecipeCommand $cmd) => $cmd->name === $recipe->name
            ));
        $this->cmdFactory
            ->expects($this->once())
            ->method('createFromRequestDto')
            ->withAnyParameters()
            ->willReturn(new UpdateRecipeCommand(
                id: $id,
                name: $recipe->name,
                servings: $recipe->servings,
                rating: $recipe->rating,
                description: $recipe->description,
                source: $recipe->source,
                steps: $recipe->steps,
                ingredients: $recipe->ingredients
            ));
        $this->logger
            ->expects($this->never())
            ->method('error')
            ->withAnyParameters();

        $controller = new UpdateRecipeController($this->commandBus, $this->cmdFactory, $this->logger);
        $response = $controller(new UpdateRecipeRouteDto($id->toString()), $recipe);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
