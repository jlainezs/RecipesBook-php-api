<?php

namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasureDto;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UnitOfMeasureCreateCommand;
use App\UnitOfMeasure\Presentation\Http\Controller\PostUnitOfMeasureController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CreateUnitOfMeasureControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private ApplicationDataValidator $validator;

    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    #[Test]
    public function test_it_validates_dispatches_command_and_returns_201_response(): void
    {
        $dto = new CreateUnitOfMeasureDto(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: 2
        );
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (UnitOfMeasureCreateCommand $cmd) => $dto->name === $cmd->name
            ));
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (UnitOfMeasureCreateCommand $cmd) => $dto->name === $cmd->name
            ));
        $controller = new PostUnitOfMeasureController($this->commandBus, $this->validator);
        $response = $controller($dto);
        $this->assertEquals(201, $response->getStatusCode());
    }
}
