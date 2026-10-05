<?php

namespace App\Tests\Unit\IngredientType\Domain\Model;

use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use App\IngredientType\Domain\Model\IngredientType;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\Exceptions\EmptyRequiredNameException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IngredientTypeTest extends TestCase
{
    #[Test]
    public function it_creates_with_a_valid_name(): void
    {
        $ingredientType = IngredientType::create(AggregateRootId::generateId()->toString(),'Vegetable');

        $this->assertSame('Vegetable', $ingredientType->getName()->value());
        $this->assertInstanceOf(AggregateRootId::class, $ingredientType->getId());
        $this->assertInstanceOf(DateTimeImmutable::class, $ingredientType->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $ingredientType->getUpdatedAt());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $first = IngredientType::create(AggregateRootId::generateId()->toString(),'Vegetable');
        $second = IngredientType::create(AggregateRootId::generateId()->toString(),'Fruit');

        $this->assertNotSame($first->getId()->toString(), $second->getId()->toString());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_on_empty_name(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        IngredientType::create(AggregateRootId::generateId()->toString(),'');
    }

    #[Test]
    public function it_throws_on_whitespace_only_name(): void
    {
        $this->expectException(EmptyRequiredNameException::class);

        IngredientType::create(AggregateRootId::generateId()->toString(),'   ');
    }

    #[Test]
    public function it_renames_successfully(): void
    {
        $ingredientType = IngredientType::create(AggregateRootId::generateId()->toString(),'Vegetable');

        $ingredientType->rename('Fruit');

        $this->assertSame('Fruit', $ingredientType->getName()->value());
    }

    #[Test]
    public function it_throws_on_rename_with_empty_name(): void
    {
        $ingredientType = IngredientType::create(AggregateRootId::generateId()->toString(),'Vegetable');

        $this->expectException(EmptyRequiredNameException::class);

        $ingredientType->rename('');
    }

    #[Test]
    public function it_throws_on_rename_with_whitespace_only_name(): void
    {
        $ingredientType = IngredientType::create(AggregateRootId::generateId()->toString(),'Vegetable');

        $this->expectException(EmptyRequiredNameException::class);

        $ingredientType->rename('   ');
    }
}
