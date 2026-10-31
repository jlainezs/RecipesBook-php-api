<?php

namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureDto;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureQuery;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureQueryFactory;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureQueryResponse;
use App\UnitOfMeasure\Presentation\Http\Controller\GetUnitsOfMeasureController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetUnitsOfMeasureListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private GetUnitsOfMeasureQueryFactory $queryFActory;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->queryFActory = $this->createMock(GetUnitsOfMeasureQueryFactory::class);
    }

    #[Test]
    public function test_it_validates_dispatches_command_and_returns_200_response(): void
    {
        $request = new GetUnitsOfMeasureDto(
            offset: 0, limit: 10
        );
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn (GetUnitsOfMeasureQuery $query) => ($query->offset >= 0 && $query->limit > 0)
            ))
            ->willReturn(new GetUnitsOfMeasureQueryResponse([]));
        $this->queryFActory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new GetUnitsOfMeasureQuery($request->offset, $request->limit));
        $controller = new GetUnitsOfMeasureController($this->queryBus, $this->queryFActory);

        $response = $controller($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
