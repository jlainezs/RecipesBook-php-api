<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientDto;
use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeCommand;
use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeDto;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Presentation\Http\Controller\UpdateIngredientTypeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class IngredientTypeUpdateControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private ApplicationDataValidator $validator;

    public function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_update_ingredient_type(): void
    {
        $ingredientType = IngredientType::create('name');
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function (UpdateIngredientTypeCommand $cmd) use ($ingredientType) {
                    return $cmd->name === $ingredientType->getName()->value();
                }
            ));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (UpdateIngredientTypeCommand $cmd) use ($ingredientType) {
                    return $cmd->id === $ingredientType->getId()->toString();
                }
            ));
        $controller = new UpdateIngredientTypeController($this->commandBus, $this->validator);
        $payload = ['name' => $ingredientType->getName()->value()];
        $request = new UpdateIngredientTypeDto($ingredientType->getName()->value());
        $id = $ingredientType->getId()->toString();

        $response = $controller($id, $request);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
