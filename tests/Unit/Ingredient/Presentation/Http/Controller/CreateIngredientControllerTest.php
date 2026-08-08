<?php
namespace App\Tests\Unit\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\IngredientCreateCommand;
use App\Ingredient\Presentation\Http\Controller\PostIngredientController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateIngredientControllerTest extends TestCase
{
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $ingredientName = 'test';

        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(function(IngredientCreateCommand $cmd) use ($ingredientName): bool {
                return $cmd->name === $ingredientName;
            }));
        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function(IngredientCreateCommand $cmd) use ($ingredientName): bool {
                return $cmd->name === $ingredientName;
            }));
        $controller = new PostIngredientController($commandBus, $validator);
        $request = Request::create(
            uri: '/api/v1/ingredients/create',
            method: 'POST',
            parameters: ['name' => $ingredientName],
            server: ['Content-Type' => 'application/json']);

        $response = $controller($request);

        $this->assertEquals(201, $response->getStatusCode());
    }
}
