<?php
namespace App\Tests\Unit\MealCourse\Application\Query\MealCourse;

use App\MealCourse\Application\Query\MealCourse\MealCoursesQuery;
use App\MealCourse\Application\Query\MealCourse\MealCoursesQueryHandler;
use App\MealCourse\Application\Query\MealCourse\MealCoursesQueryResponse;
use App\MealCourse\Application\Service\MealCourseItemsPager;
use App\MealCourse\Domain\Model\MealCourse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MealCoursesQueryHandlerTest extends TestCase
{
    private MealCourseItemsPager&MockObject $pager;
    private MealCoursesQueryHandler $handler;

    protected function setUp(): void
    {
        $this->pager = $this->createMock(MealCourseItemsPager::class);
        $this->handler = new MealCoursesQueryHandler($this->pager);
    }

    #[Test]
    public function it_returns_a_response_with_mapped_dtos(): void
    {
        $starter = MealCourse::create('Starter');
        $main = MealCourse::create('Main');

        $this->pager
            ->method('items')
            ->with(0, 20)
            ->willReturn([$starter, $main]);
        $response = ($this->handler)(new MealCoursesQuery(0, 20));

        $this->assertInstanceOf(MealCoursesQueryResponse::class, $response);
        $this->assertCount(2, $response->items);
        $this->assertSame($starter->getId()->toString(), $response->items[0]->id);
        $this->assertSame($starter->getName(), $response->items[0]->name);
        $this->assertSame($main->getId()->toString(), $response->items[1]->id);
        $this->assertSame($main->getName(), $response->items[1]->name);
    }

    #[Test]
    public function it_returns_an_empty_response_when_no_items_are_found(): void
    {
        $this->pager
            ->method('items')
            ->willReturn([]);
        $response = ($this->handler)(new MealCoursesQuery(0, 20));

        $this->assertInstanceOf(MealCoursesQueryResponse::class, $response);
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

        ($this->handler)(new MealCoursesQuery(10, 5));
    }

}
