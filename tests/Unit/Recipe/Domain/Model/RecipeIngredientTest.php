<?php

namespace App\Tests\Unit\Recipe\Domain\Model;

use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Model\RecipeIngredient;
use App\Recipe\Domain\ValueObjects\IngredientReference;
use App\Recipe\Domain\ValueObjects\UnitOfMeasureReference;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeIngredientTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_with_valid_data(): void
    {
        $ingredientId = AggregateRootId::generateId();
        $unitOfMeasureId = AggregateRootId::generateId();
        $recipe = Recipe::create(
            name: 'Salt',
            servings: 1,
            rating: 2,
            description: 'Salt',
            source: '',
            steps: [],
            ingredients: [],
        );

        $recipeIngredient = RecipeIngredient::create(
            recipe: $recipe,
            ingredient: new IngredientReference($ingredientId->getId()),
            unitOfMeasure: new UnitOfMeasureReference($unitOfMeasureId->getId()),
            quantity: 2.1,
            ordering: 1
        );

        $this->assertEquals(
            $recipeIngredient->getIngredient()->value()->toString(),
            $ingredientId->toString()
        );
        $this->assertEquals(
            $recipeIngredient->getUnitOfMeasure()->value()->toString(),
            $unitOfMeasureId->toString()
        );
        $this->assertEquals(
            1,
            $recipeIngredient->getOrdering()->value()
        );
        $this->assertEquals(
            2.1,
            $recipeIngredient->getQuantity()->value()
        );
    }

    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $ingredientId = AggregateRootId::generateId();
        $unitOfMeasureId = AggregateRootId::generateId();
        $recipe = Recipe::create(
            name: 'Salt',
            servings: 1,
            rating: 2,
            description: 'Salt',
            source: '',
            steps: [],
            ingredients: [],
        );

        $recipeIngredient1 = RecipeIngredient::create(
            recipe: $recipe,
            ingredient: new IngredientReference($ingredientId->getId()),
            unitOfMeasure: new UnitOfMeasureReference($unitOfMeasureId->getId()),
            quantity: 2.1,
            ordering: 1
        );
        $recipeIngredient2 = RecipeIngredient::create(
            recipe: $recipe,
            ingredient: new IngredientReference($ingredientId->getId()),
            unitOfMeasure: new UnitOfMeasureReference($unitOfMeasureId->getId()),
            quantity: 2.1,
            ordering: 1
        );
        $this->assertNotEquals($recipeIngredient1->getId()->toString(), $recipeIngredient2->getId()->toString());
    }
}
