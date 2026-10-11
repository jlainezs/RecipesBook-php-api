<?php
namespace App\DataFixtures\Ingredient;

use App\IngredientType\Domain\Model\IngredientType;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IngredientTypeFixtures extends Fixture
{
    public array $testIngredientTypes;

    public function __construct()
    {
        $this->testIngredientTypes = [];
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function load(ObjectManager $manager): void
    {
        $toCreate = [
            [AggregateRootId::generateId()->toString(), 'test ingredient type 1'],
            [AggregateRootId::generateId()->toString(), 'test ingredient type 2'],
            [AggregateRootId::generateId()->toString(), 'test ingredient type 3']
        ];

        foreach ($toCreate as $ingredientType)
        {
            $ingredientType = IngredientType::create($ingredientType[0], $ingredientType[1]);
            $this->testIngredientTypes[] = $ingredientType;
            $manager->persist($ingredientType);
        }

        $manager->flush();
    }
}
