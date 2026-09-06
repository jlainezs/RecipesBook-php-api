<?php
namespace App\Tests\Unit\MealCourse\Application\Command\MealCourse;

use App\MealCourse\Application\Command\MealCourse\DeleteMealCourse\DeleteMealCourseCommand;
use App\MealCourse\Application\Command\MealCourse\DeleteMealCourse\DeleteMealCourseCommandHandler;
use App\MealCourse\Domain\Exceptions\MealCourseNotFoundException;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DeleteMealCourseHandlerCommandTest extends TestCase
{
    private MealCourseRepositoryInterface $repository;
    private DeleteMealCourseCommandHandler $handler;

    public function setUp(): void
    {
        $this->repository = $this->createMock(MealCourseRepositoryInterface::class);
        $this->handler = new DeleteMealCourseCommandHandler($this->repository);
    }

    #[Test]
    public function it_deletes_the_meal_course(): void
    {
        $mealCourse = MealCourse::create("Starter");
        $id = $mealCourse->getId()->toString();

        $this->repository
            ->expects($this->once())
            ->method("findOne")
            ->with($id)
            ->willReturn($mealCourse);

        $this->repository
            ->expects($this->once())
            ->method("delete")
            ->with($mealCourse);

        ($this->handler)(new DeleteMealCourseCommand($id));
    }

    #[Test]
    public function it_throws_the_meal_course_is_not_found(): void
    {
        $mealCourse = MealCourse::create("Starter");
        $id = $mealCourse->getId()->toString();

        $this->repository
            ->expects($this->once())
            ->method("findOne")
            ->with($id)
            ->willReturn(null);

        $this->repository
            ->expects($this->never())
            ->method("delete");

        $this->expectException(MealCourseNotFoundException::class);
        ($this->handler)(new DeleteMealCourseCommand($id));
    }
}
