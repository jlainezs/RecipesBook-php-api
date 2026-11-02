<?php
namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure\CreateUnitOfMeasureCommandFactory;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure\CreateUnitOfMeasureDto;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure\CreateUnitOfMeasureCommand;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureQuery;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureResponse;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureDto;
use App\UnitOfMeasure\Presentation\Http\Controller\CreateUnitOfMeasureController;
use App\UnitOfMeasure\Presentation\Http\Resource\UnitOfMeasureResource;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateUnitOfMeasureControllerTest extends TestCase
{
    public CommandBus $commandBus;
    public QueryBus $queryBus;
    public CreateUnitOfMeasureCommandFactory $commandFactory;
    public UrlGeneratorInterface $urlGenerator;
    public ApplicationDataValidator $validator;


    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
        $this->commandFactory = new CreateUnitOfMeasureCommandFactory($this->validator);
        $this->urlGenerator = $this->createStub(UrlGeneratorInterface::class);
        $this->urlGenerator->method('generate')->willReturn('http://localhost/api/v1/units-of-measure/id');
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_201_response(): void
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
                fn (CreateUnitOfMeasureCommand $cmd) => $dto->name === $cmd->name
            ));
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (CreateUnitOfMeasureCommand $cmd) => $dto->name === $cmd->name
            ));
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->isInstanceOf(GetUnitOfMeasureQuery::class))
            ->willReturnCallback(fn (GetUnitOfMeasureQuery $query) => new GetUnitOfMeasureResponse(
                new UnitOfMeasureDto($query->id, $dto->name, $dto->symbol, $dto->unitOfMeasureType, new DateTimeImmutable(), new DateTimeImmutable())
            ));

        $controller = new CreateUnitOfMeasureController(
            $this->commandBus,
            $this->queryBus,
            $this->commandFactory,
            new UnitOfMeasureResource($this->urlGenerator)
        );
        $response = $controller($dto);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertSame('application/vnd.api+json', $response->headers->get('Content-Type'));
        $document = json_decode($response->getContent(), true);
        $this->assertSame(UnitOfMeasureResource::TYPE, $document['data']['type']);
        $this->assertSame('test', $document['data']['attributes']['name']);
    }
}
