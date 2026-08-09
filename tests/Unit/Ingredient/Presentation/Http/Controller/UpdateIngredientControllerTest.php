<?php

namespace App\Tests\Unit\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\IngredientUpdateCommand;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\Ingredient\Presentation\Http\Controller\PutIngredientController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class UpdateIngredientControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private ApplicationDataValidator $validator;

    public function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    #[Test]
    public function it_should_update_ingredient(): void
    {
        $ingredientTypeRef = new IngredientTypeReference('4d083381-6833-4e29-819b-35b96c36bb6c');
        $ingredient = Ingredient::create('ingredient', 'description', $ingredientTypeRef);
        $cmd = new IngredientUpdateCommand($ingredient->getId(), 'ingredient', 'description', $ingredientTypeRef);
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function (IngredientUpdateCommand $cmdV) use ($cmd){
                    return $cmdV->id === $cmd->id;
                }
            ));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (IngredientUpdateCommand $cmdV) use ($cmd){
                    return $cmdV->id === $cmd->id;
                }
            ));

        $controller = new PutIngredientController($this->commandBus, $this->validator);
        $payload = [
            'name' => $cmd->name,
            'description' => $cmd->description,
            'type' => $cmd->ingredientTypeId
        ];

        $request = Request::create(
            uri: '/ingredients/api/v1/' . $ingredient->getId()->toString(),
            method: 'PUT',
            server: ['Content-Type' => 'application/json'],
            content: json_encode($payload)
        );
        $request->attributes->add(['id' => $ingredient->getId()->toString()]);
        $response = $controller($request);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
