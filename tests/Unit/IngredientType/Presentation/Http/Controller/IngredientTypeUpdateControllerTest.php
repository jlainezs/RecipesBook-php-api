<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeCommand;
use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeCommandFactory;
use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeDto;
use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeRouteDto;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Presentation\Http\Controller\UpdateIngredientTypeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class IngredientTypeUpdateControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private UpdateIngredientTypeCommandFactory $cmdFactory;

    public function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->cmdFactory = $this->createMock(UpdateIngredientTypeCommandFactory::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_update_ingredient_type(): void
    {
        $ingredientType = IngredientType::create(AggregateRootId::generateId()->toString(),'name');
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function (UpdateIngredientTypeCommand $cmd) use ($ingredientType) {
                    return $cmd->name === $ingredientType->getName()->value();
                }
            ));
        $this->cmdFactory
            ->expects($this->once())
            ->method('createFromRequest')
            ->withAnyParameters()
            ->willReturn(
                new UpdateIngredientTypeCommand(
                    $ingredientType->getId()->toString(),
                    $ingredientType->getName()->value()
                )
            );
        $controller = new UpdateIngredientTypeController($this->commandBus, $this->cmdFactory);
        $paramRoute = new UpdateIngredientTypeRouteDto($ingredientType->getId()->toString());
        $requestBody = new UpdateIngredientTypeDto($ingredientType->getName()->value());

        $response = $controller(
            $paramRoute,
            $requestBody
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
