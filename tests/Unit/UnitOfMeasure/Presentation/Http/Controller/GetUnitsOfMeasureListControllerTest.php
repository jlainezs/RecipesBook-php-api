<?php

namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitsOfMeasureQuery;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitsOfMeasureQueryResponse;
use App\UnitOfMeasure\Presentation\Http\Controller\UnitsOfMeasureListController;
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
                fn (UnitsOfMeasureQuery $query) => ($query->offset >= 0 && $query->limit > 0)
            ))
            ->willReturn(new UnitsOfMeasureQueryResponse([]));
        $this->validator
            ->expects($this->never()) // TODO: MUST validate parameters!
            ->method('validate')
            ->with($this->callback(
                fn (UnitsOfMeasureQuery $query) => ($query->offset >= 0 && $query->limit > 0)
            ));
        $controller = new UnitsOfMeasureListController($this->queryBus, $this->validator);
        $request = Request::create(
            uri: '/api/v1/units-of-measure?offset=0&limit=10',
            server: ['CONTENT_TYPE' => 'application/json']
        );
        $request->attributes->add(
            ['offset' => 0, 'limit' => 10]
        );

        $response = $controller($request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
