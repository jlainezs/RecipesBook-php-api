<?php

namespace App\Tests\Unit\MealCourse\Domain\Model;

use App\MealCourse\Domain\Exceptions\MealCourseEmptyNameException;
use App\MealCourse\Domain\Model\MealCourse;
use App\Shared\Domain\ValueObject\AggregateRootId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MealCourseTest extends TestCase
{
    #[Test]
    public function it_creates_with_a_valid_name(): void
    {
        $mealCourse = MealCourse::create('Starter');
        $this->assertInstanceOf(AggregateRootId::class, $mealCourse->getId());
        $this->assertInstanceOf(DateTimeImmutable::class, $mealCourse->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $mealCourse->getUpdatedAt());
    }

    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $firstMealCourse = MealCourse::create('Starter');
        $secondMealCourse = MealCourse::create('Main');

        $this->assertNotEquals($firstMealCourse->getId(), $secondMealCourse->getId());
    }

    #[Test]
    public function it_throws_on_empty_name(): void
    {
        $this->expectException(MealCourseEmptyNameException::class);
        MealCourse::create('');
    }

    #[Test]
    public function it_throws_on_whitespace_name(): void
    {
        $this->expectException(MealCourseEmptyNameException::class);
        MealCourse::create('  ');
    }

    #[Test]
    public function it_renames_successfully(): void
    {
        $mealCourse = MealCourse::create('Starter');
        $mealCourse->rename('Main');
        $this->assertEquals('Main', $mealCourse->getName());
    }

    #[Test]
    public function it_throws_when_rename_with_empty_name(): void
    {
        $mealCourse = MealCourse::create('Starter');
        $this->expectException(MealCourseEmptyNameException::class);
        $mealCourse->rename('');
    }

    #[Test]
    public function it_throws_when_rename_with_white_space_name(): void
    {
        $mealCourse = MealCourse::create('Starter');
        $this->expectException(MealCourseEmptyNameException::class);
        $mealCourse->rename(' ');
    }
}
