<?php
namespace App\Tests\Unit\Recipe\Domain\ValueObjects;

use App\Recipe\Domain\Exceptions\RecipeInvalidRatingException;
use App\Recipe\Domain\ValueObjects\RecipeRating;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class RecipeRatingTest extends TestCase
{
    #[Test]
    #[DataProvider('goodRatings')]
    public function it_should_keep_the_values(?int $rating)
    {
        $recipeRating = new RecipeRating($rating);
        $this->assertSame($recipeRating->value(), $rating);
    }

    #[Test]
    #[DataProvider('wrongRatings')]
    public function it_should_not_allow_wrong_values(?int $value)
    {
        $this->expectException(RecipeInvalidRatingException::class);
        new RecipeRating($value);
    }

    public static function goodRatings(): iterable
    {
        yield '1' => [1];
        yield '2' => [2];
        yield '3' => [3];
        yield '4' => [4];
        yield '5' => [5];
        yield 'null' => [null];
    }
    public static function wrongRatings(): iterable
    {
        yield 'negative' => [-1];
        yield 'zero' => [0];
    }
}
