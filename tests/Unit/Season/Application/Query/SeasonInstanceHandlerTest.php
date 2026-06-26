<?php

namespace App\Tests\Unit\Season\Application\Query;

use App\IngredientType\Application\Query\IngredientType\IngredientTypeInstanceHandler;
use App\IngredientType\Application\Query\IngredientType\IngredientTypeInstanceQuery;
use App\IngredientType\Application\Query\IngredientType\IngredientTypeInstanceResponse;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Season\Application\Query\Season\SeasonInstanceHandler;
use App\Season\Application\Query\Season\SeasonInstanceQuery;
use App\Season\Application\Query\Season\SeasonInstanceResponse;
use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Model\Season;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SeasonInstanceHandlerTest extends TestCase
{
    private SeasonRepositoryInterface&MockObject $repository;
    private SeasonInstanceHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(SeasonRepositoryInterface::class);
        $this->handler = new SeasonInstanceHandler($this->repository);
    }

    #[Test]
    public function it_returns_a_response_with_dto_when_found(): void
    {
        $season = Season::create('Summer');
        $id = $season->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($season);

        $response = ($this->handler)(new SeasonInstanceQuery($id));

        $this->assertInstanceOf(SeasonInstanceResponse::class, $response);
        $this->assertNotNull($response->season);
        $this->assertSame($id, $response->season->id);
        $this->assertSame('Summer', $response->season->name);
    }

    #[Test]
    public function it_throws_when_season_is_not_found(): void
    {
        $id = '3fa85f64-5717-4562-b3fc-2c963f66afa6';

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $this->expectException(SeasonNotFoundException::class);

        ($this->handler)(new SeasonInstanceQuery($id));
    }
}
