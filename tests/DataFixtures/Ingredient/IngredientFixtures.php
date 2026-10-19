<?php

namespace App\Tests\DataFixtures\Ingredient;

use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IngredientFixtures extends Fixture
{
    public array $testIngredients;
    public IngredientTypeFixtures $ingredientTypeFixtures;

    public function __construct()
    {
        $this->testIngredients = [];
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function load(ObjectManager $manager): void
    {
        $this->ingredientTypeFixtures = new IngredientTypeFixtures();
        $this->ingredientTypeFixtures->load($manager);

        $rawIngredients = [
            [
                "id" => AggregateRootId::generateId()->toString(),
                "name" => "An ingredient test",
                "description" =>"The description of this test ingredient",
                "ingredientTypeId" => $this->ingredientTypeFixtures->testIngredientTypes[0]->getId()->toString()
            ],
            [
                "id" => AggregateRootId::generateId()->toString(),
                "name" => "An ingredient test 2",
                "description" =>"The description of this test ingredient 2",
                "ingredientTypeId" => $this->ingredientTypeFixtures->testIngredientTypes[1]->getId()->toString()
            ]
        ];

        foreach ($rawIngredients as $rawIngredient)
        {
            $ingredient = Ingredient::create(
                $rawIngredient["id"],
                $rawIngredient["name"],
                $rawIngredient["description"],
                new IngredientTypeReference($rawIngredient["ingredientTypeId"])
            );
            $this->testIngredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
