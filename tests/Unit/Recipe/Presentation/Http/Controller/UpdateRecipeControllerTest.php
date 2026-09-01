<?php
namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\RecipeUpdate\RecipeUpdateCommand;
use App\Recipe\Application\Command\RecipeUpdate\RecipeUpdateDto;
use App\Recipe\Presentation\Http\Controller\UpdateRecipeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class UpdateRecipeControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private ApplicationDataValidator $validator;
    private LoggerInterface $logger;

    public function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_update_ingredient_type(): void
    {
        $id = AggregateRootId::generateId();
        $recipe = new RecipeUpdateDto(
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
                fn(RecipeUpdateCommand $cmd) => $cmd->name === $recipe->name
            ));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn(RecipeUpdateCommand $cmd) => $cmd->name === $recipe->name
            ));
        $this->logger
            ->expects($this->never())
            ->method('error')
            ->withAnyParameters();

        $controller = new UpdateRecipeController($this->commandBus, $this->validator, $this->logger);
        $response = $controller($id->toString(), $recipe);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
