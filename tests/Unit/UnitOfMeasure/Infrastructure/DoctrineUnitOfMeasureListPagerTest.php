<?php

namespace App\Tests\Unit\UnitOfMeasure\Infrastructure;

use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use App\UnitOfMeasure\Infrastructure\DoctrineUnitsOfMeasureListPager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DoctrineUnitOfMeasureListPagerTest extends  TestCase
{
    private DoctrineUnitsOfMeasureListPager $pager;
    private UnitOfMeasureRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UnitOfMeasureRepositoryInterface::class);
        $this->pager = new DoctrineUnitsOfMeasureListPager($this->repository);
    }

    #[Test]
    public function it_should_return_an_empty_list_when_there_are_no_seasons()
    {
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $result = $this->pager->items(1,10);
        $this->assertCount(0, $result);
    }
}
