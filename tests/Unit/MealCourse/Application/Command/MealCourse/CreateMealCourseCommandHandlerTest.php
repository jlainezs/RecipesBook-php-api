<?php
namespace App\Tests\Unit\MealCourse\Application\Command\MealCourse;

use App\MealCourse\Application\Command\MealCourse\CreateMealCourse\CreateMealCourseCommand;
use App\MealCourse\Application\Command\MealCourse\CreateMealCourse\CreateMealCourseCommandHandler;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\Exceptions\EmptyRequiredNameException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CreateMealCourseCommandHandlerTest extends TestCase
{
    private MealCourseRepositoryInterface $repository;
    private CreateMealCourseCommandHandler $handler;

    public function setUp(): void
    {
        $this->repository = $this->createMock(MealCourseRepositoryInterface::class);
        $this->handler = new CreateMealCourseCommandHandler($this->repository);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_and_saves_the_meal_course(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(MealCourse::class));
        ($this->handler)(new CreateMealCourseCommand($id, "Starter"));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_and_does_not_save_when_name_is_empty(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $this->repository
            ->expects($this->never())
            ->method('save');
        $this->expectException(EmptyRequiredNameException::class);
        ($this->handler)(new CreateMealCourseCommand($id, ""));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_and_does_not_save_when_name_is_whitespace_only(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $this->repository
            ->expects($this->never())
            ->method('save');
        $this->expectException(EmptyRequiredNameException::class);
        ($this->handler)(new CreateMealCourseCommand($id, "    "));
    }
}
