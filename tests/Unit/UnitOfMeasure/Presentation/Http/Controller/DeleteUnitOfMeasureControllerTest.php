<?php

namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure\DeleteUnitOfMeasureCommand;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure\DeleteUnitOfMeasureCommandFactory;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure\DeleteUnitOfMeasureRouteDto;
use App\UnitOfMeasure\Presentation\Http\Controller\DeleteUnitOfMeasureController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteUnitOfMeasureControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private DeleteUnitOfMeasureCommandFactory $commandFactory;

    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->commandFactory = $this->createMock(DeleteUnitOfMeasureCommandFactory::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function test_it_validates_dispatches_command_and_returns_204_response(): void
    {
        $id = AggregateRootId::generateId();
        $request = new DeleteUnitOfMeasureRouteDto($id->toString());
        $cmd = new DeleteUnitOfMeasureCommand($id->toString());

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($cmd);
        $this->commandFactory
            ->expects($this->once())
            ->method('createFromRequest')
            ->withAnyParameters()
            ->willReturn(
                new DeleteUnitOfMeasureCommand($id->toString())
            );
        $controller = new DeleteUnitOfMeasureController($this->commandBus, $this->commandFactory);
        $response = $controller($request);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
