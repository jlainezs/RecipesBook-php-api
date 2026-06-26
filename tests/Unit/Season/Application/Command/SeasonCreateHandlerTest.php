<?php
namespace App\Tests\Unit\Season\Application\Command;

use App\Season\Application\Command\Season\SeasonCreateCommand;
use App\Season\Application\Command\Season\SeasonCreateHandler;
use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use App\Season\Domain\Model\Season;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SeasonCreateHandlerTest extends TestCase
{
    private SeasonRepositoryInterface&MockObject $repository;
    private SeasonCreateHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(SeasonRepositoryInterface::class);
        $this->handler = new SeasonCreateHandler($this->repository);
    }

    #[Test]
    public function it_creates_and_saves_the_season(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Season::class));

        ($this->handler)(new SeasonCreateCommand('Summer'));
    }

    #[Test]
    public function it_throws_and_does_not_save_when_name_is_empty(): void
    {
        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(SeasonEmptyNameException::class);

        ($this->handler)(new SeasonCreateCommand(''));
    }

    #[Test]
    public function it_throws_and_does_not_save_when_name_is_whitespace_only(): void
    {
        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(SeasonEmptyNameException::class);

        ($this->handler)(new SeasonCreateCommand('   '));
    }
}
