<?php
namespace App\DataFixtures\Recipe;

use App\Recipe\Domain\Model\Recipe;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture
{
    public array $testRecipes;

    public function loadTestIngredients(ObjectManager $manager): void
    {

    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadTestIngredients($manager);
        $rawObjects = [
            "id" => AggregateRootId::generateId()->toString(),
            "name" => "Test Recipe",
            "servings" => 4,
            "raging" => 3,
            "description" => "Test Recipe Description",
            "source" => "Test Recipe Source",
            "steps" => ["Step 1", "Step 2"],
            "ingredients" => ["Ingredient 1", "Ingredient 2", "Ingredient 3"],
        ];

        foreach ($rawObjects as $rawObject) {
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
            $recipe->setSteps($rawObject["steps"]);
            $recipe->setIngredients($rawObject["ingredients"]);
            $this->testRecipes[] = $recipe;
            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
