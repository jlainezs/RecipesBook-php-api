<?php
namespace App\Tests\DataFixtures\Recipe;

use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Model\RecipeIngredient;
use App\Recipe\Domain\Model\RecipeStep;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Shared\Domain\ValueObjects\IngredientReference;
use App\Shared\Domain\ValueObjects\UnitOfMeasureReference;
use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\Ingredient\IngredientTypeFixtures;
use App\Tests\DataFixtures\UnitOfMeasure\UnitOfMeasureFixture;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture
{
    public array $testRecipes;
    public IngredientFixtures $ingredientFixtures;
    public UnitOfMeasureFixture $unitOfMeasureFixtures;

    /**
     * @throws EmptyIdNotAllowedException
     */
    private function loadTestIngredients(ObjectManager $manager): void
    {
        $this->ingredientFixtures = new IngredientFixtures();
        $this->ingredientFixtures->load($manager);
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws EmptyIdNotAllowedException
     */
    private function loadTestUnitsOfMeasure(ObjectManager $manager): void
    {
        $this->unitOfMeasureFixtures = new UnitOfMeasureFixture();
        $this->unitOfMeasureFixtures->load($manager);
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws UnitOfMeasureSymbolLengthException
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadTestIngredients($manager);
        $this->loadTestUnitsOfMeasure($manager);

        $rawObjects = [
            [
                "id" => AggregateRootId::generateId()->toString(),
                "name" => "Test Recipe",
                "servings" => 4,
                "raging" => 3,
                "description" => "Test Recipe Description",
                "source" => "Test Recipe Source",
                "steps" => [
                    ["description" => "Step 1", "ordering" => 1],
                    ["description" => "Step 2", "ordering" => 2]
                ],
                "ingredients" => [
                    [
                        "ingredientId" => $this->ingredientFixtures->testIngredients[0]->getId()->toString(),
                        "unitOfMeasureId" => $this->unitOfMeasureFixtures->testUnitOfMeasures[0]->getId()->toString(),
                        "quantity" => 1, "ordering" => 1
                    ],
                    [
                        "ingredientId" => $this->ingredientFixtures->testIngredients[0]->getId()->toString(),
                        "unitOfMeasureId" => $this->unitOfMeasureFixtures->testUnitOfMeasures[1]->getId()->toString(),
                        "quantity" => 5, "ordering" => 2
                    ],
                ],
            ]
        ];

        foreach ($rawObjects as $rawObject)
        {
            $recipe = Recipe::create(
                $rawObject["id"],
                $rawObject["name"],
                $rawObject["servings"],
                $rawObject["raging"],
                $rawObject["description"],
                $rawObject["source"],
                [],
                [],
            );

            $recipeSteps = [];
            foreach ($rawObject["steps"] as $step)
            {
                $recipeSteps[] = RecipeStep::create(
                    $recipe,
                    $step["ordering"],
                    $step["description"],
                );
            }
            $recipeIngredients = [];
            foreach ($rawObject["ingredients"] as $ingredient)
            {
                $recipeIngredients[] = RecipeIngredient::create(
                    $recipe,
                    new IngredientReference($ingredient["ingredientId"]),
                    new UnitOfMeasureReference($ingredient["unitOfMeasureId"]),
                    $ingredient["quantity"],
                    $ingredient["ordering"]
                );
            }

            $recipe->setSteps($recipeSteps);
            $recipe->setIngredients($recipeIngredients);
            $this->testRecipes[] = $recipe;
            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
