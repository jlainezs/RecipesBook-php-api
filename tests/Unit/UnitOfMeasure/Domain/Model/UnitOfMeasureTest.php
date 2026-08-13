<?php

namespace App\Tests\Unit\UnitOfMeasure\Domain\Model;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnitOfMeasureTest extends TestCase
{
    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_with_a_valid_name(): void
    {
        $uom = UnitOfMeasure::create(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $this->assertSame('test', $uom->getName());
        $this->assertSame('t', $uom->getSymbol()->value());
        $this->assertSame(UnitOfMeasureEnum::Units, $uom->getUomType());
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_with_a_valid_id(): void
    {
        $uom = UnitOfMeasure::create(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $this->assertInstanceOf(AggregateRootId::class, $uom->getId());
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $uom1 = UnitOfMeasure::create(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $uom2 = UnitOfMeasure::create(
            name: 'test1',
            symbol: 't1',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $this->assertNotEquals($uom1->getId()->toString(), $uom2->getId()->toString());
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider('invalidNames')]
    public function it_throws_on_invalid_name(string $invalidName): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        UnitOfMeasure::create(
            name: $invalidName,
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_renames_successfully(): void
    {
        $uom = UnitOfMeasure::create(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $uom->rename('new name');
        $this->assertEquals('new name', $uom->getName());
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider('invalidNames')]
    public function it_throws_on_rename_with_whitespace(string $invalidName): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        UnitOfMeasure::create(
            name: $invalidName,
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
    }

    public static function invalidNames(): iterable
    {
        yield 'empty' => [''];
        yield 'white_spaces' => ['   '];
    }
}
