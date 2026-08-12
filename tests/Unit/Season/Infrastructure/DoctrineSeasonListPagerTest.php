<?php
namespace App\Tests\Unit\Season\Infrastructure;

use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Season\Infrastructure\DoctrineSeasonsListPager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DoctrineSeasonListPagerTest extends TestCase
{
    private DoctrineSeasonsListPager $pager;
    private SeasonRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->repository = $this->createMock(SeasonRepositoryInterface::class);
        $this->pager = new DoctrineSeasonsListPager($this->repository);
    }

    #[Test]
    public function it_should_return_an_empty_list_when_there_are_no_seasons()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $result = $this->pager->items(1, 10);
        $this->assertCount(0, $result);
    }
}
