<?php

namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\DeleteIngredientType\DeleteIngredientTypeCommand;
use App\IngredientType\Application\Command\IngredientType\DeleteIngredientType\DeleteIngredientTypeCommandFactory;
use App\IngredientType\Application\Command\IngredientType\DeleteIngredientType\DeleteIngredientTypeDto;
use App\IngredientType\Presentation\Http\Controller\DeleteIngredientTypeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteIngredientTypeControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_the_request_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory = $this->createMock(DeleteIngredientTypeCommandFactory::class);
        $id = AggregateRootId::generateId()->toString();

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();
        $cmdFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(
                new DeleteIngredientTypeCommand($id)
            );

        $controller = new DeleteIngredientTypeController($commandBus, $cmdFactory);
        $response = $controller(new DeleteIngredientTypeDto($id));
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
