<?php

namespace App\Tests\Unit\Season\Application\Query;

use App\Season\Application\Query\Season\GetSeason\GetSeasonQuery;
use App\Season\Application\Query\Season\GetSeason\GetSeasonQueryHandler;
use App\Season\Application\Query\Season\GetSeason\GetSeasonQueryResponse;
use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Model\Season;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SeasonInstanceHandlerTest extends TestCase
{
    private SeasonRepositoryInterface&MockObject $repository;
    private GetSeasonQueryHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(SeasonRepositoryInterface::class);
        $this->handler = new GetSeasonQueryHandler($this->repository);
    }

    #[Test]
    public function it_returns_a_response_with_dto_when_found(): void
    {
        $season = Season::create('Summer');
        $id = $season->getId()->toString();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($season);

        $response = ($this->handler)(new GetSeasonQuery($id));

        $this->assertInstanceOf(GetSeasonQueryResponse::class, $response);
        $this->assertNotNull($response->season);
        $this->assertSame($id, $response->season->id);
        $this->assertSame('Summer', $response->season->name);
    }

    #[Test]
    public function it_throws_when_season_is_not_found(): void
    {
        $id = '3fa85f64-5717-4562-b3fc-2c963f66afa6';

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $this->expectException(SeasonNotFoundException::class);

        ($this->handler)(new GetSeasonQuery($id));
    }
}
