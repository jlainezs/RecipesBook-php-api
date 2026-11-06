<?php
namespace App\Tests\Unit\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientCommand;
use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientCommandFactory;
use App\Ingredient\Presentation\Http\Controller\CreateIngredientController;
use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientDto;
use App\Ingredient\Presentation\Http\Resoource\IngredientResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class CreateIngredientControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $urlGeneratorInterface = $this->createMock(UrlGeneratorInterface::class);
        $cmdFactory = new CreateIngredientCommandFactory();

        $ingredientName = 'test';
        $ingredientTypeId = 'ce1c5c7b-3566-476c-951e-95d984fba7f6';
        $ingredientDescription = 'ingredient description';

        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function(CreateIngredientCommand $cmd) use ($ingredientName): bool {
                return $cmd->name === $ingredientName;
            }));
        $urlGeneratorInterface
            ->expects($this->atLeastOnce())
            ->method('generate')
            ->withAnyParameters();

        $controller = new CreateIngredientController($commandBus, $cmdFactory, new IngredientResource($urlGeneratorInterface));
        $request = new CreateIngredientDto($ingredientName, $ingredientTypeId, $ingredientDescription);
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
