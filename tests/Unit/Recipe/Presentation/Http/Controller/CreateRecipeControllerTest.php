<?php
namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\RecipeCreate\RecipeCreateCommand;
use App\Recipe\Application\Command\RecipeCreate\RecipeCreateDto;
use App\Recipe\Presentation\Http\Controller\PostRecipeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CreateRecipeControllerTest extends TestCase
{
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $recipe = new RecipeCreateDto(
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
                fn (RecipeCreateCommand $cmd) => $cmd->name === $recipe->name
            ));
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (RecipeCreateCommand $cmd) => $cmd->name === $recipe->name
            ));
        $controller = new PostRecipeController($commandBus, $validator);

        $response = $controller($recipe);
        $this->assertEquals(201, $response->getStatusCode());
    }
}
