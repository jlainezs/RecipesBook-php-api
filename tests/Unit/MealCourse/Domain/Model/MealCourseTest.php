<?php

namespace App\Tests\Unit\MealCourse\Domain\Model;

use App\MealCourse\Domain\Model\MealCourse;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\Exceptions\EmptyRequiredNameException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MealCourseTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_with_a_valid_name(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $mealCourse = MealCourse::create($id, 'Starter');
        $this->assertInstanceOf(AggregateRootId::class, $mealCourse->getId());
        $this->assertInstanceOf(DateTimeImmutable::class, $mealCourse->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $mealCourse->getUpdatedAt());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $id1 = AggregateRootId::generateId()->toString();
        $firstMealCourse = MealCourse::create($id1, 'Starter');
        $id2 = AggregateRootId::generateId()->toString();
        $secondMealCourse = MealCourse::create($id2, 'Main');

        $this->assertNotEquals($firstMealCourse->getId(), $secondMealCourse->getId());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_on_empty_name(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $this->expectException(EmptyRequiredNameException::class);
        MealCourse::create($id, '');
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_on_whitespace_name(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $this->expectException(EmptyRequiredNameException::class);
        MealCourse::create($id, '  ');
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_renames_successfully(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $mealCourse = MealCourse::create($id, 'Starter');
        $mealCourse->rename('Main');
        $this->assertEquals('Main', $mealCourse->getName());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_rename_with_empty_name(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $mealCourse = MealCourse::create($id, 'Starter');
        $this->expectException(EmptyRequiredNameException::class);
        $mealCourse->rename('');
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_rename_with_white_space_name(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $mealCourse = MealCourse::create($id, 'Starter');
        $this->expectException(EmptyRequiredNameException::class);
        $mealCourse->rename(' ');
    }
}
