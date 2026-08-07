<?php
namespace App\Tests\Unit\ShoppingList\Domain\Model;

use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Domain\Model\ShoppingList;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShoppingListTest extends TestCase
{
    #[Test]
    public function it_creates_with_a_valid_name(): void
    {
        $sl = ShoppingList::create('Shopping List');
        $this->assertSame('Shopping List', $sl->getName()->value());
        $this->assertInstanceOf(DateTimeImmutable::class, $sl->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $sl->getUpdatedAt());
        $this->assertNull($sl->getScheduledFor());
    }

    #[Test]
    public function it_creates_with_a_valid_id(): void
    {
        $sl = ShoppingList::create('Shopping List');
        $this->assertInstanceOf(AggregateRootId::class, $sl->getId());
    }

    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $sl1 = ShoppingList::create('Shopping List');
        $sl2 = ShoppingList::create('Shopping List');
        $this->assertNotSame($sl1->getId()->toString(), $sl2->getId()->toString());
    }

    #[Test]
    public function it_throws_on_empty_name(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        ShoppingList::create('');
    }

    #[Test]
    public function it_throws_on_whitespace_only_name(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        ShoppingList::create('  ');
    }

    #[Test]
    public function it_renames_successfully(): void
    {
        $sl = ShoppingList::create('Shopping List');
        $sl->rename('New Name');
        $this->assertSame('New Name', $sl->getName()->value());
    }

    #[Test]
    public function it_throws_on_rename_with_whitespace(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        $sl = ShoppingList::create('Shopping List');
        $this->expectException(EmptyRequiredNameException::class);
        $sl->rename(' ');
    }

    #[Test]
    public function it_throws_on_rename_with_empty_name(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        $sl = ShoppingList::create('Shopping List');
        $this->expectException(EmptyRequiredNameException::class);
        $sl->rename('');
    }
}
