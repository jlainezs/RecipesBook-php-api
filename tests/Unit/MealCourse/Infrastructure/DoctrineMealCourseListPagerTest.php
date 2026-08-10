<?php
namespace App\Tests\Unit\MealCourse\Infrastructure;

use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use App\MealCourse\Infrastructure\DoctrineMealCourseListPager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DoctrineMealCourseListPagerTest extends TestCase
{
    private DoctrineMealCourseListPager $pager;
    private MealCourseRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->repository = $this->createMock(MealCourseRepositoryInterface::class);
        $this->pager = new DoctrineMealCourseListPager($this->repository);
    }

    #[Test]
    public function it_should_return_an_empty_list_when_there_are_no_ingredients()
    {
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $result = $this->pager->items(1, 10);

        $this->assertCount(0, $result);
    }
}
