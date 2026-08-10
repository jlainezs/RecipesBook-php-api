<?php
namespace App\Tests\Unit\Recipe\Domain\ValueObjects;

use App\Recipe\Domain\Exceptions\RecipeIngredientInvalidQuantityException;
use App\Recipe\Domain\ValueObjects\RecipeIngredientQuantity;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeIngredientQuantityTest extends TestCase
{
    #[Test]
    #[DataProvider('wrongQuantities')]
    public function it_should_throw_withWrongQuantities(float $quantity)
    {
        $this->expectException(RecipeIngredientInvalidQuantityException::class);
        new RecipeIngredientQuantity($quantity);
    }

    #[Test]
    #[DataProvider('goodQuantities')]
    public function it_should_keep_the_values(float $quantity)
    {
        $ingredientQuantity = new RecipeIngredientQuantity($quantity);
        $this->assertSame($ingredientQuantity->value(), $quantity);
    }

    #[Test]
    #[DataProvider('goodQuantities')]
    public function it_should_convert_to_string_properly(float $quantity)
    {
        $ingredientQuantity = new RecipeIngredientQuantity($quantity);
        $this->assertSame((string)$ingredientQuantity, (string)$quantity);
    }

    public static function wrongQuantities(): iterable
    {
        yield 'negative' => [-1];
    }

    public static function goodQuantities(): iterable
    {
        yield 'integer' => [1];
        yield 'zero' => [0];
        yield 'float' => [1.3];
    }

}
