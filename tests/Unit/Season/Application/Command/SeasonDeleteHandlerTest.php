<?php
namespace App\Tests\Unit\Season\Application\Command;

use App\IngredientType\Application\Command\IngredientType\IngredientTypeDeleteCommand;
use App\IngredientType\Application\Command\IngredientType\IngredientTypeDeleteHandler;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Season\Application\Command\Season\SeasonDeleteCommand;
use App\Season\Application\Command\Season\SeasonDeleteHandler;
use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Model\Season;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SeasonDeleteHandlerTest extends TestCase
{
    private SeasonRepositoryInterface&MockObject $repository;
    private SeasonDeleteHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(SeasonRepositoryInterface::class);
        $this->handler = new SeasonDeleteHandler($this->repository);
    }

    #[Test]
    public function it_deletes_when_season_is_found(): void
    {
        $season = Season::create('Summer');
        $id = $season->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($season);

        $this->repository
            ->expects($this->once())
            ->method('delete')
            ->with($season);

        ($this->handler)(new SeasonDeleteCommand($id));
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
            ->method('delete');

        $this->expectException(SeasonNotFoundException::class);

        ($this->handler)(new SeasonDeleteCommand($id));
    }
}
