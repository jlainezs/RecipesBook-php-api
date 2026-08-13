<?php
namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureInstanceQuery;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use App\UnitOfMeasure\Presentation\Http\Controller\GetUnitOfMeasureController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class GetUniOfMeasureControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private ApplicationDataValidator $validator;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $uom = UnitOfMeasure::create(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $query = new UnitOfMeasureInstanceQuery($uom->getId()->toString());
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($query);
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($query)
            ->willReturn($uom);
        $controller = new GetUnitOfMeasureController($this->queryBus, $this->validator);
        $request = Request::create(
            uri: '/api/url/units-of-measure/' . $uom->getId()->toString(),
            server: ['Content-Type' => 'application/json']
        );
        $request->attributes->add(['id' => $uom->getId()->toString()]);

        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
