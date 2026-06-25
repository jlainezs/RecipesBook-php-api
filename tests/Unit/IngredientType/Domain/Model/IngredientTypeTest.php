<?php

namespace App\Tests\Unit\IngredientType\Domain\Model;

use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use App\IngredientType\Domain\Model\IngredientType;
use App\Shared\Domain\ValueObject\AggregateRootId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IngredientTypeTest extends TestCase
{
    #[Test]
    public function it_creates_with_a_valid_name(): void
    {
        $ingredientType = IngredientType::create('Vegetable');

        $this->assertSame('Vegetable', $ingredientType->getName());
        $this->assertInstanceOf(AggregateRootId::class, $ingredientType->getId());
        $this->assertInstanceOf(DateTimeImmutable::class, $ingredientType->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $ingredientType->getUpdatedAt());
    }

    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $first = IngredientType::create('Vegetable');
        $second = IngredientType::create('Fruit');

        $this->assertNotSame($first->getId()->toString(), $second->getId()->toString());
    }

    #[Test]
    public function it_throws_on_empty_name(): void
    {
        $this->expectException(IngredientTypeEmptyNameException::class);

        IngredientType::create('');
    }

    #[Test]
    public function it_throws_on_whitespace_only_name(): void
    {
        $this->expectException(IngredientTypeEmptyNameException::class);

        IngredientType::create('   ');
    }

    #[Test]
    public function it_renames_successfully(): void
    {
        $ingredientType = IngredientType::create('Vegetable');

        $ingredientType->rename('Fruit');

        $this->assertSame('Fruit', $ingredientType->getName());
    }

    #[Test]
    public function it_throws_on_rename_with_empty_name(): void
    {
        $ingredientType = IngredientType::create('Vegetable');

        $this->expectException(IngredientTypeEmptyNameException::class);

        $ingredientType->rename('');
    }

    #[Test]
    public function it_throws_on_rename_with_whitespace_only_name(): void
    {
        $ingredientType = IngredientType::create('Vegetable');

        $this->expectException(IngredientTypeEmptyNameException::class);

        $ingredientType->rename('   ');
    }
}
