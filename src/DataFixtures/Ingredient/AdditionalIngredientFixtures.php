<?php
namespace App\DataFixtures\Ingredient;

use App\IngredientType\Application\Service\IngredientTypeItemsPager;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Infrastructure\Repository\IngredientTypeRepository;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdditionalIngredientFixtures extends Fixture
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
            'test ingredient type 1',
            'test ingredient type 2'.
            'test ingredient type 3'
        ];

        foreach ($toCreate as $ingredientType)
        {
            $manager->persist(IngredientType::create($ingredientType));
        }

        $manager->flush();
    }
}
