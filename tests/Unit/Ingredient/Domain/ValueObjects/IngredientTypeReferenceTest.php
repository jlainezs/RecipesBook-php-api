<?php
namespace App\Tests\Unit\Ingredient\Domain\ValueObjects;

use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class IngredientTypeReferenceTest extends TestCase
{
    #[Test]
    #[DataProvider('someUuids')]
    /**
     * @throws EmptyIdNotAllowedException
    */
    function it_should_be_equal_to_another_ingredient_type_reference_with_same_value(string $uuid)
    {
        $itr1 = new IngredientTypeReference($uuid);
        $itr2 = new IngredientTypeReference($uuid);

        $this->assertSame($itr1->value()->toString(), $itr2->value()->toString());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider('someUuids')]
    function it_should_not_be_equal_to_another_ingredient_type_reference_with_different_value(string $uuid)
    {
        $itr1 = new IngredientTypeReference($uuid);
        $itr2 = new IngredientTypeReference('daa87941-1027-4a6c-88d2-97705853bbf8');

        $this->assertNotSame($itr1->value()->toString(), $itr2->value()->toString());
    }


    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider('wrongUuids')]
    public function it_should_throw_an_exception_when_provided_with_an_invalid_uuid(string $uuid): void
    {
        $this->expectException(InvalidArgumentException::class);
        new IngredientTypeReference($uuid);
    }
    #[Test]
    #[DataProvider('emptyUuids')]
    public function it_should_throw_an_exception_when_provided_with_an_empty_uuid(string $uuid): void
    {
        $this->expectException(EmptyIdNotAllowedException::class);
        new IngredientTypeReference($uuid);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider('someUuids')]
    public function it_should_keep_the_uuid_value(string $uuid): void
    {
        $ref = new IngredientTypeReference($uuid);
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
        yield 'wrongUuid3' => ['    '];
    }

    public static function emptyUuids(): iterable
    {
        yield 'emptyUuid1' => [''];
    }
}
