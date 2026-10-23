<?php
namespace App\Tests\Unit\ShoppingList\Application\Command\ShoppingListCreate;

use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\Exceptions\EmptyRequiredNameException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListCommand;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListCommandHandler;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ShoppingListCreateCommandHandlerTest extends TestCase
{
    private ShoppingListRepositoryInterface&MockObject $repository;
    private IngredientRepositoryInterface $ingredientRepository;
    private UnitOfMeasureRepositoryInterface $unitOfMeasureRepository;
    private CreateShoppingListCommandHandler $handler;

    public function setUp(): void
    {
        $this->repository = $this->createMock(ShoppingListRepositoryInterface::class);
        $this->ingredientRepository = $this->createStub(IngredientRepositoryInterface::class);
        $this->unitOfMeasureRepository = $this->createStub(UnitOfMeasureRepositoryInterface::class);
        $this->handler = new CreateShoppingListCommandHandler(
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
        ($this->handler)(new CreateShoppingListCommand(
            id: AggregateRootId::generateId()->toString(),
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
        ($this->handler)(new CreateShoppingListCommand(
            id: AggregateRootId::generateId()->toString(),
            name: '',
            items: []
        ));
    }
}
