<?php
namespace App\Tests\Unit\Recipe\Domain\Model;

use App\Recipe\Domain\Exceptions\RecipeInvalidRatingException;
use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\Model\Recipe;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\Exceptions\EmptyRequiredNameException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_with_a_valid_name(): void
    {
        $id =  AggregateRootId::generateId()->toString();
        $recipe = Recipe::create($id, 'Recipe Name', 4, 5, '', '', [], []);
        $this->assertInstanceOf(AggregateRootId::class, $recipe->getId());
        $this->assertInstanceOf(DateTimeImmutable::class, $recipe->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $recipe->getUpdatedAt());
        $this->assertEquals('Recipe Name', $recipe->getName());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $id1 = AggregateRootId::generateId()->toString();
        $id2 = AggregateRootId::generateId()->toString();
        $recipe1 = Recipe::create($id1, 'Recipe Name', 4, 5, '', '', [], []);
        $recipe2 = Recipe::create($id2, 'Another recipe Name', 4, 5, '', '', [], []);
        $this->assertNotEquals($recipe1->getId(), $recipe2->getId());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_on_empty_name(): void
    {
        $id =  AggregateRootId::generateId()->toString();
        $this->expectException(EmptyRequiredNameException::class);
        Recipe::create($id, '', 4, 5, '', '', [], []);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_renames_successfully(): void
    {
        $id =  AggregateRootId::generateId()->toString();
        $recipe = Recipe::create($id, 'a recipe', 4, 5, '', '', [], []);
        $recipe->rename('new name', 4, 5, '', '', [],);
        $this->assertEquals('new name', $recipe->getName());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_on_empty_rename(): void
    {
        $id =  AggregateRootId::generateId()->toString();
        $recipe = Recipe::create($id, 'a recipe', 4, 5, '', '', [], []);
        $this->expectException(EmptyRequiredNameException::class);
        $recipe->rename('');
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_rename_with_space(): void
    {
        $id =  AggregateRootId::generateId()->toString();
        $recipe = Recipe::create($id, 'a recipe', 4, 5, '', '', [], []);
        $this->expectException(EmptyRequiredNameException::class);
        $recipe->rename(' ');
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_creating_with_0_servings() {
        $this->expectException(RecipeInvalidServingsException::class);
        $id =  AggregateRootId::generateId()->toString();
        Recipe::create($id, 'Recipe Name', 0, 5, '', '', [], []);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_with_servings_below_1() {
        $id =  AggregateRootId::generateId()->toString();
        $recipe = Recipe::create($id, 'Recipe Name', 4, 5, '', '', [], []);
        $this->expectException(RecipeInvalidServingsException::class);
        $recipe->setServings(0);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function servings_is_updated() {
        $id =  AggregateRootId::generateId()->toString();
        $recipe = Recipe::create($id, 'Recipe Name', 4, 5, '', '', [], []);
        $recipe->setServings(5);
        $this->assertEquals(5, $recipe->getServings()->value());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throw_when_creating_with_rating_below_0() {
        $id =  AggregateRootId::generateId()->toString();
        $this->expectException(RecipeInvalidRatingException::class);
        Recipe::create($id, 'Recipe name', 1, -1, '', '', [], []);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throw_when_creating_with_rating_over_5() {
        $id =  AggregateRootId::generateId()->toString();
        $this->expectException(RecipeInvalidRatingException::class);
        Recipe::create($id, 'Recipe name', 1, 6, '', '', [], []);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_setting_rating_over_5() {
        $id =  AggregateRootId::generateId()->toString();
        $recipe = Recipe::create($id, 'Recipe name', 1, 5, '', '', [], []);
        $this->expectException(RecipeInvalidRatingException::class);
        $recipe->setRating(6);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_setting_rating_under_0() {
        $id =  AggregateRootId::generateId()->toString();
        $recipe = Recipe::create($id, 'Recipe name', 1, 5, '', '', [], []);
        $this->expectException(RecipeInvalidRatingException::class);
        $recipe->setRating(-1);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function rating_is_updated() {
        $id =  AggregateRootId::generateId()->toString();
        $recipe = Recipe::create($id, 'Recipe name', 1, 5, '', '', [], []);
        $recipe->setRating(4);
        $this->assertEquals(4, $recipe->getRating()->value());
    }
}
