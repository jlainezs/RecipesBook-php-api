<?php

namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureDto;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureQuery;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureQueryResponse;
use App\UnitOfMeasure\Presentation\Http\Controller\GetUnitsOfMeasureController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class GetUnitsOfMeasureListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private ApplicationDataValidator $validator;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    #[Test]
    public function test_it_validates_dispatches_command_and_returns_200_response(): void
    {
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn (GetUnitsOfMeasureQuery $query) => ($query->offset >= 0 && $query->limit > 0)
            ))
            ->willReturn(new GetUnitsOfMeasureQueryResponse([]));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (GetUnitsOfMeasureQuery $query) => ($query->offset >= 0 && $query->limit > 0)
            ));
        $controller = new GetUnitsOfMeasureController($this->queryBus, $this->validator);
        $request = new GetUnitsOfMeasureDto(
            offset: 0, limit: 10
        );
        $response = $controller($request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
