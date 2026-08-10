<?php
namespace App\Tests\Unit\Recipe\Domain\ValueObjects;

use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\ValueObjects\RecipeServings;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeServingsTest extends TestCase
{
    #[Test]
    #[DataProvider('goodRatings')]
    public function it_should_keep_the_values(int $servings)
    {
        $recipeServings = new RecipeServings($servings);
        $this->assertSame($servings, $recipeServings->value());
    }

    #[Test]
    #[DataProvider('wrongServings')]
    public function it_throws_exception_when_servings_are_wrong(int $servings): void
    {
        $this->expectException(RecipeInvalidServingsException::class);
        new RecipeServings($servings);
    }

    public static function wrongServings():iterable
    {
        yield 'negative' => [-1];
        yield 'zero' => [0];
    }

    public static function goodRatings(): iterable
    {
        yield '1' => [1];
        yield '2' => [2];
        yield '3' => [3];
        yield '4' => [4];
        yield '5' => [5];
    }
}
