<?php
namespace App\Tests\Unit\MealCourse\Application\Command\MealCourse;

use App\MealCourse\Application\Command\MealCourse\MealCourseCreateCommand;
use App\MealCourse\Application\Command\MealCourse\MealCourseCreateCommandHandler;
use App\MealCourse\Domain\Exceptions\MealCourseEmptyNameException;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MealCourseCreateCommandHandlerTest extends TestCase
{
    private MealCourseRepositoryInterface $repository;
    private MealCourseCreateCommandHandler $handler;

    public function setUp(): void
    {
        $this->repository = $this->createMock(MealCourseRepositoryInterface::class);
        $this->handler = new MealCourseCreateCommandHandler($this->repository);
    }

    #[Test]
    public function it_creates_and_saves_the_meal_course(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(MealCourse::class));
        ($this->handler)(new MealCourseCreateCommand("Starter"));
    }

    #[Test]
    public function it_throws_and_does_not_save_when_name_is_empty(): void
    {
        $this->repository
            ->expects($this->never())
            ->method('save');
        $this->expectException(MealCourseEmptyNameException::class);
        ($this->handler)(new MealCourseCreateCommand(""));
    }

    #[Test]
    public function it_throws_and_does_not_save_when_name_is_whitespace_only(): void
    {
        $this->repository
            ->expects($this->never())
            ->method('save');
        $this->expectException(MealCourseEmptyNameException::class);
        ($this->handler)(new MealCourseCreateCommand("    "));
    }
}
