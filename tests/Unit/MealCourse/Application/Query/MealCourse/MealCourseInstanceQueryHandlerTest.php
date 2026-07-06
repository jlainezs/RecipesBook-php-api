<?php

namespace App\Tests\Unit\MealCourse\Application\Query\MealCourse;

use App\MealCourse\Application\Query\MealCourse\MealCourseInstanceQuery;
use App\MealCourse\Application\Query\MealCourse\MealCourseInstanceQueryHandler;
use App\MealCourse\Application\Query\MealCourse\MealCourseInstanceResponse;
use App\MealCourse\Domain\Exceptions\MealCourseNotFoundException;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MealCourseInstanceQueryHandlerTest extends TestCase
{
    private MealCourseRepositoryInterface&MockObject $repository;
    private MealCourseInstanceQueryHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MealCourseRepositoryInterface::class);
        $this->handler = new MealCourseInstanceQueryHandler($this->repository);
    }

    #[Test]
    public function it_returns_a_response_with_dto_when_found(): void
    {
        $mealCourse = MealCourse::create('Starter');
        $id = $mealCourse->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($mealCourse);

        $response = ($this->handler)(new MealCourseInstanceQuery($id));

        $this->assertInstanceOf(MealCourseInstanceResponse::class, $response);
        $this->assertNotNull($response->mealCourse);
        $this->assertSame($id, $response->mealCourse->id);
        $this->assertSame('Starter', $response->mealCourse->name);
    }

    #[Test]
    public function it_throws_when_meal_course_is_not_found(): void
    {
        $id = '3fa85f64-5717-4562-b3fc-2c963f66afa6';

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn(null);
        $this->expectException(MealCourseNotFoundException::class);
        $response = ($this->handler)(new MealCourseInstanceQuery($id));
    }
}
