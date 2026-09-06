<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeCommand;
use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeDto;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Presentation\Http\Controller\CreateIngredientTypeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateIngredientTypeControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $ingredientType = IngredientType::create('name');

        $validator
            ->expects($this->once())
            ->method('validate')
            ->with(
                $this->callback(
                function (CreateIngredientTypeCommand $cmd) use ($ingredientType) {
                    return $cmd->name === $ingredientType->getName()->value();
                }
            ));
        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function(CreateIngredientTypeCommand $cmd) use ($ingredientType) {
                    return $cmd->name === $ingredientType->getName()->value();
                }
            ));
        $controller = new CreateIngredientTypeController($commandBus, $validator);
        $request = new CreateIngredientTypeDto(
            name: $ingredientType->getName()->value()
        );
        $response = $controller($request);

        $this->assertEquals(201, $response->getStatusCode());
    }
}
