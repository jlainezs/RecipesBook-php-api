<?php
namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure\UpdateUnitOfMeasureCommand;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure\UpdateUnitOfMeasureCommandFactory;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure\UpdateUnitOfMeasureDto;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure\UpdateUnitOfMeasureRouteDto;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureQuery;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureResponse;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureDto;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use App\UnitOfMeasure\Presentation\Http\Controller\UpdateUnitOfMeasureController;
use App\UnitOfMeasure\Presentation\Http\Resource\UnitOfMeasureResource;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UpdateUnitOfMeasureControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private QueryBus $queryBus;
    private UpdateUnitOfMeasureCommandFactory $commandFactory;

    public function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->commandFactory = $this->createMock(UpdateUnitOfMeasureCommandFactory::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_200_with_the_resource(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $routeDto = new UpdateUnitOfMeasureRouteDto($id);
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
        $this->commandFactory
            ->expects($this->once())
            ->method('createFromRequest')
            ->withAnyParameters()
            ->willReturn(
                new UpdateUnitOfMeasureCommand($id, $request->name, $request->symbol, UnitOfMeasureEnum::from($request->unitOfMeasureType))
            );
        $controller = new UpdateUnitOfMeasureController(
            $this->commandBus,
            $this->commandFactory,
            new UnitOfMeasureResource($this->createStub(UrlGeneratorInterface::class))
        );

        $response = $controller($routeDto, $request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $document = json_decode($response->getContent());
        $this->assertNull($document);
    }
}
