<?php

namespace App\Tests\Unit\Season\Application\Command;

use App\Season\Application\Command\Season\SeasonUpdateCommand;
use App\Season\Application\Command\Season\SeasonUpdateCommandHandler;
use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Model\Season;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SeasonUpdateHandlerTest extends TestCase
{
    private SeasonRepositoryInterface&MockObject $repository;
    private SeasonUpdateCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(SeasonRepositoryInterface::class);
        $this->handler = new SeasonUpdateCommandHandler($this->repository);
    }

    #[Test]
    public function it_renames_and_saves_when_season_is_found(): void
    {
        $season = Season::create('Summer');
        $id = $season->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($season);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($season);

        ($this->handler)(new SeasonUpdateCommand($id, 'Winter'));

        $this->assertSame('Winter', $season->getName());
    }

    #[Test]
    public function it_throws_when_season_is_not_found(): void
    {
        $id = '3fa85f64-5717-4562-b3fc-2c963f66afa6';

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(SeasonNotFoundException::class);

        ($this->handler)(new SeasonUpdateCommand($id, 'Summer'));
    }

    #[Test]
    public function it_throws_and_does_not_save_when_new_name_is_empty(): void
    {
        $ingredientType = Season::create('Summer');
        $id = $ingredientType->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($ingredientType);

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(SeasonEmptyNameException::class);

        ($this->handler)(new SeasonUpdateCommand($id, ''));
    }
}
