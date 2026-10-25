<?php
namespace App\Tests\Unit\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\DeleteIngredient\DeleteIngredientCommand;
use App\Ingredient\Application\Command\Ingredient\DeleteIngredient\DeleteIngredientCommandFactory;
use App\Ingredient\Application\Command\Ingredient\DeleteIngredient\DeleteIngredientDto;
use App\Ingredient\Presentation\Http\Controller\DeleteIngredientController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DeleteIngredientControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_the_request_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory = $this->createMock(DeleteIngredientCommandFactory::class);
        $id = AggregateRootId::generateId()->toString();

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();
        $cmdFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new DeleteIngredientCommand($id));

        $controller = new DeleteIngredientController($commandBus, $cmdFactory);
        $dto = new DeleteIngredientDto($id);
        $response = $controller($dto);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
