<?php

namespace App\Tests\Unit\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientCommand;
use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientDto;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\Ingredient\Presentation\Http\Controller\UpdateIngredientController;
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
        $cmd = new UpdateIngredientCommand($ingredient->getId(), 'ingredient', 'description', $ingredientTypeRef);
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function (UpdateIngredientCommand $cmdV) use ($cmd){
                    return $cmdV->id === $cmd->id;
                }
            ));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (UpdateIngredientCommand $cmdV) use ($cmd){
                    return $cmdV->id === $cmd->id;
                }
            ));

        $controller = new UpdateIngredientController($this->commandBus, $this->validator);
        $request = new UpdateIngredientDto(
            $cmd->name,
            $cmd->ingredientTypeId,
            $cmd->description
        );

        $response = $controller($ingredient->getId()->toString(), $request);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
