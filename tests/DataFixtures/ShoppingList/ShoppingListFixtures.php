<?php
namespace App\Tests\DataFixtures\ShoppingList;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Shared\Domain\ValueObjects\IngredientReference;
use App\Shared\Domain\ValueObjects\UnitOfMeasureReference;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Domain\Model\ShoppingListItem;
use App\ShoppingList\Domain\ValueObjects\ShoppingListItemQuantity;
use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\UnitOfMeasure\UnitOfMeasureFixture;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ShoppingListFixtures extends Fixture
{
    public array $testData = [];

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
                'id' => AggregateRootId::generateId()->toString(),
                'name' => 'Test Shopping List',
                'items' => [
                    [
                        'ingredient' => $this->ingredientFixtures->testIngredients[0]->getId()->toString(),
                        'unitOfMeasure' => $this->unitOfMeasureFixtures->testData[0]->getId()->toString(),
                        'quantity' => 1
                    ],
                    [
                        'ingredient' => $this->ingredientFixtures->testIngredients[1]->getId()->toString(),
                        'unitOfMeasure' => $this->unitOfMeasureFixtures->testData[1]->getId()->toString(),
                        'quantity' => 1
                    ]
                ]
            ]
        ];

        foreach ($rawObjects as $rawObject)
        {
            $shoppingList = ShoppingList::create(
                $rawObject['id'],
                $rawObject['name'],
                []
            );

            foreach ($rawObject['items'] as $item)
            {
                $shoppingListItem = ShoppingListItem::create(
                    $shoppingList,
                    new IngredientReference($item['ingredient']),
                    new UnitOfMeasureReference($item['unitOfMeasure']),
                    new ShoppingListItemQuantity($item['quantity'])
                );

                $shoppingList->addItem($shoppingListItem);
            }

            $this->testData[] = $shoppingList;
            $manager->persist($shoppingList);
        }

        $manager->flush();
    }
}
