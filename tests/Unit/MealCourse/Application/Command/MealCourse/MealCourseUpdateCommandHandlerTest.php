<?php

namespace App\Tests\Unit\MealCourse\Application\Command\MealCourse;

use App\MealCourse\Application\Command\MealCourse\MealCourseUpdateCommand;
use App\MealCourse\Application\Command\MealCourse\MealCourseUpdateCommandHandler;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MealCourseUpdateCommandHandlerTest extends TestCase
{
    private MealCourseRepositoryInterface&MockObject $repository;
    private MealCourseUpdateCommandHandler $handler;

    public function setUp(): void
    {
        $this->repository = $this->createMock(MealCourseRepositoryInterface::class);
        $this->handler = new MealCourseUpdateCommandHandler($this->repository);
    }

    #[Test]
    public function it_renames_and_saves_the_meal_course(): void
    {
        $mealCourse = MealCourse::create("Starter");
        $id = $mealCourse->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($mealCourse);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($mealCourse);

        ($this->handler)(new MealCourseUpdateCommand($id, 'Main course'));
        $this->assertSame('Main course', $mealCourse->getName());
    }
}
