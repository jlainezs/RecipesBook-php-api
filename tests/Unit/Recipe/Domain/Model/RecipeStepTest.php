<?php
namespace App\Tests\Unit\Recipe\Domain\Model;

use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Model\RecipeStep;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\InvalidOrderingException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeStepTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_a_recipe_step(): void
    {
        $recipe = Recipe::create(
            name: 'Salt',
            servings: 1,
            rating: 2,
            description: 'Salt',
            source: '',
            steps: [],
            ingredients: [],
        );

        $recipeStep = RecipeStep::create(
            recipe: $recipe,
            ordering: 1,
            description: 'Salt'
        );

        $this->assertSame($recipe->getId()->toString(), $recipeStep->getRecipe()->getId()->toString());
    }

    #[Test]
    public function it_throws_exception_when_wrong_ordering(): void
    {
        $recipe = Recipe::create(
            name: 'Salt',
            servings: 1,
            rating: 2,
            description: 'Salt',
            source: '',
            steps: [],
            ingredients: [],
        );

        $this->expectException(InvalidOrderingException::class);
        $recipeStep = RecipeStep::create(
            recipe: $recipe,
            ordering: -1,
            description: 'Salt'
        );
    }
}
