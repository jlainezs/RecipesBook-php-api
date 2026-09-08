<?php

namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure\DeleteUnitOfMeasureCommand;
use App\UnitOfMeasure\Presentation\Http\Controller\DeleteUnitOfMeasureController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class DeleteUnitOfMeasureControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private ApplicationDataValidator $validator;

    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function test_it_validates_dispatches_command_and_returns_204_response(): void
    {
        $id = AggregateRootId::generateId();
        $cmd = new DeleteUnitOfMeasureCommand($id->toString());

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($cmd);
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($cmd);
        $controller = new DeleteUnitOfMeasureController($this->commandBus, $this->validator);
        $response = $controller($id->toString());
        $this->assertEquals(204, $response->getStatusCode());
    }
}
