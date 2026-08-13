<?php

namespace App\Tests\Unit\UnitOfMeasure\Application\Query\UnitsOfMeasure;

use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitsOfMeasureQuery;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitsOfMeasureQueryHandler;
use App\UnitOfMeasure\Application\Service\UnitsOfMeasureItemsPager;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnitsOfMeasureQueryHandlerTest extends TestCase
{
    private UnitsOfMeasureItemsPager $pager;
    private UnitsOfMeasureQueryHandler $handler;

    protected function setUp(): void
    {
        $this->pager = $this->createMock(UnitsOfMeasureItemsPager::class);
        $this->handler = new UnitsOfMeasureQueryHandler($this->pager);
    }

    #[Test]
    public function it_returns_a_response_with_mapped_dto(): void
    {
        $uom1 = UnitOfMeasure::create(
            'uom1',
            'U1',
            UnitOfMeasureEnum::Units
        );
        $uom2 = UnitOfMeasure::create(
            'uom2',
            'U2',
            UnitOfMeasureEnum::Units
        );
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(0,20)
            ->willReturn([$uom1, $uom2]);

        $response = ($this->handler)(new UnitsOfMeasureQuery(0,20));
        $this->assertCount(2, $response->items);
        $this->assertEquals($uom1->getId()->toString(), $response->items[0]->id);
        $this->assertEquals($uom1->getName(), $response->items[0]->name);
        $this->assertEquals($uom2->getId()->toString(), $response->items[1]->id);
        $this->assertEquals($uom2->getName(), $response->items[1]->name);
    }

    #[Test]
    public function it_returns_an_empty_response_when_no_items_exist(): void
    {
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(0,20)
            ->willReturn([]);

        $response = ($this->handler)(new UnitsOfMeasureQuery(0,20));
        $this->assertCount(0, $response->items);
    }

    #[Test]
    public function it_forwards_offset_and_limit_to_the_pager(): void
    {
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(0,5)
            ->willReturn([]);

        $response = ($this->handler)(new UnitsOfMeasureQuery(0,5));
        $this->assertCount(0, $response->items);
    }
}
