<?php
namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure\UpdateUnitOfMeasureCommand;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure\UpdateUnitOfMeasureDto;
use App\UnitOfMeasure\Presentation\Http\Controller\UpdateUnitOfMeasureController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateUnitOfMeasureControllerTest extends TestCase
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
    public function it_dispatches_command_and_returns_204(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $request = new UpdateUnitOfMeasureDto(
            'name',
            'symbol',
            1
        );
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (UpdateUnitOfMeasureCommand $cmd) => $cmd->id === $id
            ));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (UpdateUnitOfMeasureCommand $cmd) => $cmd->id === $id
            ));
        $controller = new UpdateUnitOfMeasureController($this->commandBus, $this->validator);

        $response = $controller($id, $request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
