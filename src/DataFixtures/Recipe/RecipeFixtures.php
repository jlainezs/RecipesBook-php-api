<?php
namespace App\DataFixtures\Recipe;

use App\Recipe\Domain\Model\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture
{
    public array $testRecipes;

    public function __construct()
    {
        $this->testRecipes = [];
    }

    public function load(ObjectManager $manager)
    {
        $rawObjects = [];

        foreach ($rawObjects as $rawObject) {
            $recipe = Recipe::create();
        }
    }
}
