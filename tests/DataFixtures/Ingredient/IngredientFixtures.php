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

    public function __construct()
    {
        $this->testIngredients = [];
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function load(ObjectManager $manager): void
    {
        $rawIngredients = [
            [
                "id" => AggregateRootId::generateId()->toString(),
                "name" => "An ingredient test",
                "description" =>"The description of this test ingredient",
                "ingredientTypeId" => "84d77bf6-43d6-4d4e-b559-3a203e750eeb"
            ],
            [
                "id" => AggregateRootId::generateId()->toString(),
                "name" => "An ingredient test 2",
                "description" =>"The description of this test ingredient 2",
                "ingredientTypeId" => "84d77bf6-43d6-4d4e-b559-3a203e750eeb"
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
