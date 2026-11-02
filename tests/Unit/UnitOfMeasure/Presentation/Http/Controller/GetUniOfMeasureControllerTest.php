<?php
namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureQuery;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureQueryFactory;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureResponse;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureRouteQueryDto;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureDto;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use App\UnitOfMeasure\Presentation\Http\Controller\GetUnitOfMeasureController;
use App\UnitOfMeasure\Presentation\Http\Resource\UnitOfMeasureResource;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetUniOfMeasureControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private GetUnitOfMeasureQueryFactory $queryFactory;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->queryFactory = $this->createMock(GetUnitOfMeasureQueryFactory::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $uom = new UnitOfMeasureDto($id, 'test', 't', UnitOfMeasureEnum::Units->value, new DateTimeImmutable(), new DateTimeImmutable());
        $query = new GetUnitOfMeasureQuery($id);
        $request = new GetUnitOfMeasureRouteQueryDto($id);
        $this->queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn($query);
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($query)
            ->willReturn(new GetUnitOfMeasureResponse($uom));
        $controller = new GetUnitOfMeasureController(
            $this->queryBus,
            $this->queryFactory,
            new UnitOfMeasureResource($this->createStub(UrlGeneratorInterface::class))
        );
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $document = json_decode($response->getContent(), true);
        $this->assertSame($id, $document['data']['id']);
    }
}
