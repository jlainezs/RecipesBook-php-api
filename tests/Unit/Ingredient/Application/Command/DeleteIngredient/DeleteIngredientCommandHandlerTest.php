<?php
namespace App\Tests\Unit\Ingredient\Application\Command\DeleteIngredient;

use App\Ingredient\Application\Command\Ingredient\DeleteIngredient\DeleteIngredientCommand;
use App\Ingredient\Application\Command\Ingredient\DeleteIngredient\DeleteIngredientCommandHandler;
use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteIngredientCommandHandlerTest extends TestCase
{
    private IngredientRepositoryInterface&MockObject $repository;
    private DeleteIngredientCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IngredientRepositoryInterface::class);
        $this->handler = new DeleteIngredientCommandHandler($this->repository);
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

        ($this->handler)(new DeleteIngredientCommand($id));
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
        ($this->handler)(new DeleteIngredientCommand($id));
    }
}
