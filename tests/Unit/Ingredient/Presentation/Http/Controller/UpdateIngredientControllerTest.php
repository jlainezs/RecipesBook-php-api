<?php
namespace App\Tests\Unit\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientCommand;
use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientCommandFactory;
use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientDto;
use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientRouteDto;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\Ingredient\Presentation\Http\Controller\UpdateIngredientController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UpdateIngredientControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private UpdateIngredientCommandFactory $cmdFactory;

    public function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->cmdFactory = $this->createMock(UpdateIngredientCommandFactory::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_update_ingredient(): void
    {
        $ingredientTypeRef = new IngredientTypeReference('4d083381-6833-4e29-819b-35b96c36bb6c');
        $ingredientId = AggregateRootId::generateId()->toString();
        $ingredient = Ingredient::create($ingredientId, 'ingredient', 'description', $ingredientTypeRef);
        $cmd = new UpdateIngredientCommand($ingredient->getId(), 'ingredient', 'description', $ingredientTypeRef);
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function (UpdateIngredientCommand $cmdV) use ($cmd){
                    return $cmdV->id === $cmd->id;
                }
            ));
        $this->cmdFactory
            ->expects($this->once())
            ->method('createFromRequest')
            ->withAnyParameters()
            ->willReturn(new UpdateIngredientCommand($ingredient->getId(), 'ingredient', 'description', $ingredientTypeRef));

        $controller = new UpdateIngredientController($this->commandBus, $this->cmdFactory);

        $routeParam = new UpdateIngredientRouteDto($ingredient->getId()->toString());
        $request = new UpdateIngredientDto(
            $cmd->name,
            $cmd->ingredientTypeId,
            $cmd->description
        );

        $response = $controller($routeParam, $request);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
