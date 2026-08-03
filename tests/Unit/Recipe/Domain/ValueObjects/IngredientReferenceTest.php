<?php
namespace App\Tests\Unit\Recipe\Domain\ValueObjects;

use App\Recipe\Domain\ValueObjects\IngredientReference;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class IngredientReferenceTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider('someUuids')]
    public function it_should_be_equal_to_another_ingredient_reference_with_the_same_value(string $uuid): void
    {
        $ingredientReference1 = new IngredientReference($uuid);
        $ingredientReference2 = new IngredientReference($uuid);

        $this->assertTrue($ingredientReference1->equals($ingredientReference2));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider('someUuids')]
    public function it_should_not_be_equal_to_another_ingredient_reference_with_a_different_value(string $uuid): void
    {
        $ingredientReference1 = new IngredientReference($uuid);
        $ingredientReference2 = new IngredientReference('daa87941-1027-4a6c-88d2-97705853bbf8');

        $this->assertFalse($ingredientReference1->equals($ingredientReference2));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider('wrongUuids')]
    public function it_should_throw_an_exception_when_provided_with_an_invalid_uuid(string $uuid): void
    {
        $this->expectException(InvalidArgumentException::class);
        new IngredientReference($uuid);
    }

    #[Test]
    #[DataProvider('emptyUuids')]
    public function it_should_throw_an_exception_when_provided_with_an_empty_uuid(string $uuid): void
    {
        $this->expectException(EmptyIdNotAllowedException::class);
        new IngredientReference($uuid);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider('someUuids')]
    public function it_should_keep_the_uuid_value(string $uuid): void
    {
        $ref = new IngredientReference($uuid);
        $this->assertSame($uuid, $ref->value()->toString());
    }

    public static function someUuids(): iterable
    {
        yield 'uuid1' => ['c867ebf7-60dc-44a2-9841-8cc3e9d2f2e5'];
    }

    public static function wrongUuids(): iterable
    {
        yield 'wrongUuid1' => ['not-a-uuid'];
        yield 'wrongUuid2' => ['01KYXTXNRQ1EJCJ4BM6HP474NG'];
        yield 'wrongUuid2' => ['    '];
    }

    public static function emptyUuids(): iterable
    {
        yield 'emptyUuid1' => [''];
    }
}
