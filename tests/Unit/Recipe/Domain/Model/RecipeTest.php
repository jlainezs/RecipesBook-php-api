<?php
namespace App\Tests\Unit\Recipe\Domain\Model;

use App\Recipe\Domain\Exceptions\RecipeEmptyNameException;
use App\Recipe\Domain\Exceptions\RecipeInvalidRatingException;
use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\Model\Recipe;
use App\Shared\Domain\ValueObject\AggregateRootId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeTest extends TestCase
{
    #[Test]
    public function it_creates_with_a_valid_name(): void
    {
        $recipe = Recipe::create('Recipe Name', 4, 5);
        $this->assertInstanceOf(AggregateRootId::class, $recipe->getId());
        $this->assertInstanceOf(DateTimeImmutable::class, $recipe->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $recipe->getUpdatedAt());
        $this->assertEquals('Recipe Name', $recipe->getName());
    }

    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $recipe1 = Recipe::create('Recipe Name', 4, 5);
        $recipe2 = Recipe::create('Another recipe Name', 4, 5);
        $this->assertNotEquals($recipe1->getId(), $recipe2->getId());
    }

    #[Test]
    public function it_throws_on_empty_name(): void
    {
        $this->expectException(RecipeEmptyNameException::class);
        Recipe::create('', 4, 5);
    }

    #[Test]
    public function it_renames_successfully(): void
    {
        $recipe = Recipe::create('a recipe', 4, 5);
        $recipe->rename('new name', 4, 5);
        $this->assertEquals('new name', $recipe->getName());
    }

    #[Test]
    public function it_throws_on_empty_rename(): void
    {
        $recipe = Recipe::create('a recipe', 4, 5);
        $this->expectException(RecipeEmptyNameException::class);
        $recipe->rename('');
    }

    #[Test]
    public function it_throws_when_rename_with_space(): void
    {
        $recipe = Recipe::create('a recipe', 4, 5);
        $this->expectException(RecipeEmptyNameException::class);
        $recipe->rename(' ');
    }

    #[Test]
    public function it_throws_when_creating_with_0_servings() {
        $this->expectException(RecipeInvalidServingsException::class);
        Recipe::create('Recipe Name', 0, 5);
    }

    #[Test]
    public function it_throws_with_servings_below_1() {
        $recipe = Recipe::create('Recipe Name', 4, 5);
        $this->expectException(RecipeInvalidServingsException::class);
        $recipe->setServings(0);
    }

    #[Test]
    public function servings_is_updated() {
        $recipe = Recipe::create('Recipe Name', 4, 5);
        $recipe->setServings(5);
        $this->assertEquals(5, $recipe->getServings());
    }

    #[Test]
    public function it_throw_when_creating_with_rating_below_0() {
        $this->expectException(RecipeInvalidRatingException::class);
        Recipe::create('Recipe name', 1, -1);
    }

    #[Test]
    public function it_throw_when_creating_with_rating_over_5() {
        $this->expectException(RecipeInvalidRatingException::class);
        Recipe::create('Recipe name', 1, 6);
    }

    #[Test]
    public function it_throws_when_setting_rating_over_5() {
        $recipe = Recipe::create('Recipe name', 1, 5);
        $this->expectException(RecipeInvalidRatingException::class);
        $recipe->setRating(6);
    }

    #[Test]
    public function it_throws_when_setting_rating_under_0() {
        $recipe = Recipe::create('Recipe name', 1, 5);
        $this->expectException(RecipeInvalidRatingException::class);
        $recipe->setRating(-1);
    }

    #[Test]
    public function rating_is_updated() {
        $recipe = Recipe::create('Recipe name', 1, 5);
        $recipe->setRating(4);
        $this->assertEquals(4, $recipe->getRating());
    }
}
