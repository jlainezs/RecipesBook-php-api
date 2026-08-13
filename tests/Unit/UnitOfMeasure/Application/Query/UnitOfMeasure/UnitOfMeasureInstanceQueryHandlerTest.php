<?php

namespace App\Tests\Unit\UnitOfMeasure\Application\Query\UnitOfMeasure;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureInstanceQuery;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureInstanceQueryHandler;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnitOfMeasureInstanceQueryHandlerTest extends TestCase
{
    private UnitOfMeasureInstanceQueryHandler $handler;
    private UnitOfMeasureRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UnitOfMeasureRepositoryInterface::class);
        $this->handler = new UnitOfMeasureInstanceQueryHandler($this->repository);
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws UnitOfMeasureNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_return_the_unit_of_measure(): void
    {
        $uom = UnitOfMeasure::create(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $id = $uom->getId();
        $this->repository->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($uom);
        $queryResult = ($this->handler)(new UnitOfMeasureInstanceQuery($id));
        $this->assertNotNull($queryResult);
        $this->assertEquals($id->toString(), $queryResult->unitOfMeasure->id);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_throw_when_unit_of_measure_not_found(): void
    {
        $id = AggregateRootId::generateId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);
        $this->expectException(UnitOfMeasureNotFoundException::class);
        ($this->handler)(new UnitOfMeasureInstanceQuery($id));
    }
}
