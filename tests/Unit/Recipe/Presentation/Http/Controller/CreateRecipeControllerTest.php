<?php
namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\CreateRecipe\CreateRecipeCommand;
use App\Recipe\Application\Command\CreateRecipe\CreateRecipeCommandFactory;
use App\Recipe\Application\Command\CreateRecipe\CreateRecipeDto;
use App\Recipe\Presentation\Http\Controller\CreateRecipeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateRecipeControllerTest extends TestCase
{
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $cmdFactory = new CreateRecipeCommandFactory($validator);
        $urlGeneratorInterface = $this->createMock(UrlGeneratorInterface::class);
        $recipe = new CreateRecipeDto(
            name: 'test',
            servings: 4,
            rating: 5,
            description: 'test',
            source: 'test',
            steps: [],
            ingredients: []
        );

        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (CreateRecipeCommand $cmd) => $cmd->name === $recipe->name
            ));
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (CreateRecipeCommand $cmd) => $cmd->name === $recipe->name
            ));
        $urlGeneratorInterface->expects($this->once())
            ->method('generate')
            ->withAnyParameters();
        $controller = new CreateRecipeController($commandBus, $cmdFactory, $urlGeneratorInterface);

        $response = $controller($recipe);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
