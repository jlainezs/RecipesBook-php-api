<?php

namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Query\PaginatedList\PageDto;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureDto;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureQuery;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureQueryFactory;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureQueryResponse;
use App\UnitOfMeasure\Presentation\Http\Controller\GetUnitsOfMeasureController;
use App\UnitOfMeasure\Presentation\Http\Resource\UnitOfMeasureResource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetUnitsOfMeasureListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private GetUnitsOfMeasureQueryFactory $queryFactory;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->queryFactory = $this->createMock(GetUnitsOfMeasureQueryFactory::class);
    }

    #[Test]
    public function test_it_validates_dispatches_command_and_returns_200_response(): void
    {
        $request = new GetUnitsOfMeasureDto(
            new PageDto(offset: 0, limit: 10)
        );
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn (GetUnitsOfMeasureQuery $query) => ($query->offset >= 0 && $query->limit > 0)
            ))
            ->willReturn(new GetUnitsOfMeasureQueryResponse([]));
        $this->queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new GetUnitsOfMeasureQuery($request->page->offset, $request->page->limit));
        $controller = new GetUnitsOfMeasureController(
            $this->queryBus,
            $this->queryFactory,
            new UnitOfMeasureResource(
                $this->createStub(UrlGeneratorInterface::class)
            )
        );

        $response = $controller($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $document = json_decode($response->getContent(), true);
        $this->assertSame([], $document['data']);
    }
}
