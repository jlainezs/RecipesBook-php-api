<?php
namespace App\Tests\Unit\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientCommand;
use App\Ingredient\Presentation\Http\Controller\CreateIngredientController;
use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;


class CreateIngredientControllerTest extends TestCase
{
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);

        $ingredientName = 'test';
        $ingredientTypeId = 'ce1c5c7b-3566-476c-951e-95d984fba7f6';
        $ingredientDescription = 'ingredient description';

        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(function(CreateIngredientCommand $cmd) use ($ingredientName): bool {
                return $cmd->name === $ingredientName;
            }));
        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function(CreateIngredientCommand $cmd) use ($ingredientName): bool {
                return $cmd->name === $ingredientName;
            }));
        $controller = new CreateIngredientController($commandBus, $validator);
        $request = new CreateIngredientDto($ingredientName, $ingredientTypeId, $ingredientDescription);
        $response = $controller($request);

        $this->assertEquals(201, $response->getStatusCode());
    }
}
