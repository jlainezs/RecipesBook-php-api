<?php
namespace App\Tests\Unit\Ingredient\Application\Command\IngredientDelete;

use App\Ingredient\Application\Command\Ingredient\IngredientDeleteCommand;
use App\Ingredient\Application\Command\Ingredient\IngredientDeleteCommandHandler;
use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IngredientDeleteCommandHandlerTest extends TestCase
{
    private IngredientRepositoryInterface&MockObject $repository;
    private IngredientDeleteCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IngredientRepositoryInterface::class);
        $this->handler = new IngredientDeleteCommandHandler($this->repository);
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws IngredientNotFoundException
     */
    #[Test]
    public function it_deletes_the_ingredient(): void
    {
        $ingredient = Ingredient::create('test');
        $id = $ingredient->getId();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($ingredient);

        $this->repository
            ->expects($this->once())
            ->method('delete')
            ->with($ingredient);

        ($this->handler)(new IngredientDeleteCommand($id));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_ingredient_is_not_found(): void
    {
        $id = AggregateRootId::generateId();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);
        $this->repository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(IngredientNotFoundException::class);
        ($this->handler)(new IngredientDeleteCommand($id));
    }
}
