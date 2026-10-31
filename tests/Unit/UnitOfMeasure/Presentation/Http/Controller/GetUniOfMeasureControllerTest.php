<?php
namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureQuery;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureQueryFactory;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureRouteQueryDto;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use App\UnitOfMeasure\Presentation\Http\Controller\GetUnitOfMeasureController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

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
     * @throws UnitOfMeasureSymbolLengthException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $uom = UnitOfMeasure::create(
            id: AggregateRootId::generateId()->toString(),
            name: 'test',
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $query = new GetUnitOfMeasureQuery($uom->getId()->toString());
        $request = new GetUnitOfMeasureRouteQueryDto($uom->getId()->toString());
        $this->queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn($query);
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($query)
            ->willReturn($uom);
        $controller = new GetUnitOfMeasureController($this->queryBus, $this->queryFactory);
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
