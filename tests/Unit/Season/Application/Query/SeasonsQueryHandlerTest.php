<?php

namespace App\Tests\Unit\Season\Application\Query;

use App\Season\Application\Query\Season\SeasonsQuery;
use App\Season\Application\Query\Season\SeasonsQueryHandler;
use App\Season\Application\Query\Season\SeasonsQueryResponse;
use App\Season\Application\Service\SeasonItemsPager;
use App\Season\Domain\Model\Season;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SeasonsQueryHandlerTest extends TestCase
{
    private SeasonItemsPager&MockObject $pager;
    private SeasonsQueryHandler $handler;

    protected function setUp(): void
    {
        $this->pager = $this->createMock(SeasonItemsPager::class);
        $this->handler = new SeasonsQueryHandler($this->pager);
    }

    #[Test]
    public function it_returns_a_response_with_mapped_dtos(): void
    {
        $summer = Season::create('Summer');
        $winter = Season::create('Winter');

        $this->pager
            ->method('items')
            ->with(0, 20)
            ->willReturn([$summer, $winter]);

        $response = ($this->handler)(new SeasonsQuery(0, 20));

        $this->assertInstanceOf(SeasonsQueryResponse::class, $response);
        $this->assertCount(2, $response->items);
        $this->assertSame($summer->getId()->toString(), $response->items[0]->id);
        $this->assertSame('Summer', $response->items[0]->name);
        $this->assertSame($winter->getId()->toString(), $response->items[1]->id);
        $this->assertSame('Winter', $response->items[1]->name);
    }

    #[Test]
    public function it_returns_an_empty_response_when_no_items_exist(): void
    {
        $this->pager
            ->method('items')
            ->willReturn([]);

        $response = ($this->handler)(new SeasonsQuery());

        $this->assertInstanceOf(SeasonsQueryResponse::class, $response);
        $this->assertEmpty($response->items);
    }

    #[Test]
    public function it_forwards_offset_and_limit_to_the_pager(): void
    {
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(10, 5)
            ->willReturn([]);

        ($this->handler)(new SeasonsQuery(10, 5));
    }
}
