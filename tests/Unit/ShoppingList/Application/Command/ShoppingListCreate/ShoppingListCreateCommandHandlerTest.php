<?php
namespace App\Tests\Unit\ShoppingList\Application\Command\ShoppingListCreate;

use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\Exceptions\EmptyRequiredNameException;
use App\ShoppingList\Application\Command\ShoppingListCreate\ShoppingListCreateCommand;
use App\ShoppingList\Application\Command\ShoppingListCreate\ShoppingListCreateCommandHandler;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ShoppingListCreateCommandHandlerTest extends TestCase
{
    private ShoppingListRepositoryInterface&MockObject $repository;
    private ShoppingListCreateCommandHandler $handler;

    public function setUp(): void
    {
        $this->repository = $this->createMock(ShoppingListRepositoryInterface::class);
        $this->ingredientRepository = $this->createMock(IngredientRepositoryInterface::class);
        $this->unitOfMeasureRepository = $this->createMock(UnitOfMeasureRepositoryInterface::class);
        $this->handler = new ShoppingListCreateCommandHandler(
            $this->repository,
            $this->ingredientRepository,
            $this->unitOfMeasureRepository
        );
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_and_saves_the_shopping_list(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(ShoppingList::class));
        ($this->handler)(new ShoppingListCreateCommand(
            name: 'a shopping list',
            items: []
        ));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_and_does_not_saves_when_name_is_empty(): void
    {
        $this->repository
            ->expects($this->never())
            ->method('save');
        $this->expectException(EmptyRequiredNameException::class);
        ($this->handler)(new ShoppingListCreateCommand(
            name: '',
            items: []
        ));
    }
}
