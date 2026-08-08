<?php
namespace App\Tests\Unit\Ingredient\Application\Command\IngredientUpdate;

use App\Ingredient\Application\Command\Ingredient\IngredientUpdateCommand;
use App\Ingredient\Application\Command\Ingredient\IngredientUpdateCommandHandler;
use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\IngredientType\Application\Query\IngredientType\FindIngredientTypeReferenceQuery;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IngredientUpdateCommandHandlerTest extends TestCase
{
    private IngredientRepositoryInterface $repository;
    private IngredientUpdateCommandHandler $handler;
    private QueryBus&MockObject $queryBus;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IngredientRepositoryInterface::class);
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->handler = new IngredientUpdateCommandHandler($this->repository, $this->queryBus);
    }

    /**
     * @throws IngredientTypeNotFoundException
     * @throws EmptyIdNotAllowedException
     * @throws IngredientNotFoundException
     */
    #[Test]
    public function it_should_update_ingredient(): void
    {
        $ingredientTypeId = "b4bac84a-9193-4405-98b7-7e53a65cc9a8";
        $ingredientTypeRef = new IngredientTypeReference($ingredientTypeId);
        $ingredient = Ingredient::create('test', 'test desc', $ingredientTypeRef);

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($ingredient->getId())
            ->willReturn($ingredient);
        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Ingredient::class));
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->isInstanceOf(FindIngredientTypeReferenceQuery::class))
            ->willReturn($ingredientTypeRef);
        ($this->handler)(new IngredientUpdateCommand(
            id: $ingredient->getId()->toString(),
            name: "ingredient 1",
            description: "tastes yummy",
            ingredientTypeId: $ingredientTypeId
        ));
    }

    /**
     * @throws IngredientTypeNotFoundException
     * @throws EmptyIdNotAllowedException
     * @throws IngredientNotFoundException
     */
    #[Test]
    public function it_throws_when_setting_empty_name(): void
    {
        $ingredientTypeId = "b4bac84a-9193-4405-98b7-7e53a65cc9a8";
        $ingredientTypeRef = new IngredientTypeReference($ingredientTypeId);
        $ingredient = Ingredient::create('test', 'test desc', $ingredientTypeRef);

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($ingredient->getId())
            ->willReturn($ingredient);
        $this->repository
            ->expects($this->never())
            ->method('save')
            ->with($this->isInstanceOf(Ingredient::class));
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->isInstanceOf(FindIngredientTypeReferenceQuery::class))
            ->willReturn($ingredientTypeRef);

        $this->expectException(EmptyRequiredNameException::class);

        ($this->handler)(new IngredientUpdateCommand(
            id: $ingredient->getId()->toString(),
            name: "",
            description: "tastes yummy",
            ingredientTypeId: $ingredientTypeId
        ));
    }

    /**
     * @throws IngredientTypeNotFoundException
     * @throws IngredientNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_ingredient_type_not_found(): void
    {
        $ingredientTypeId = "b4bac84a-9193-4405-98b7-7e53a65cc9a8";
        $ingredientTypeRef = new IngredientTypeReference($ingredientTypeId);
        $ingredient = Ingredient::create('test', 'test desc', $ingredientTypeRef);

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($ingredient->getId())
            ->willReturn($ingredient);
        $this->repository
            ->expects($this->never())
            ->method('save')
            ->with($this->isInstanceOf(Ingredient::class));
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->isInstanceOf(FindIngredientTypeReferenceQuery::class))
            ->willReturn(null);

        $this->expectException(IngredientTypeNotFoundException::class);

        ($this->handler)(new IngredientUpdateCommand(
            id: $ingredient->getId()->toString(),
            name: "test",
            description: "tastes yummy",
            ingredientTypeId: $ingredientTypeId
        ));
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws IngredientNotFoundException
     * @throws IngredientTypeNotFoundException
     */
    #[Test]
    public function it_throws_when_ingredient_not_found(): void
    {
        $ingredientTypeId = "b4bac84a-9193-4405-98b7-7e53a65cc9a8";
        $ingredientTypeRef = new IngredientTypeReference($ingredientTypeId);
        //$ingredient = Ingredient::create('test', 'test desc', $ingredientTypeRef);
        $ingredientId = AggregateRootId::generateId();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($ingredientId)
            ->willReturn(null);
        $this->repository
            ->expects($this->never())
            ->method('save');
        $this->queryBus
            ->expects($this->never())
            ->method('ask')
            ->with($this->isInstanceOf(FindIngredientTypeReferenceQuery::class));

        $this->expectException(IngredientNotFoundException::class);

        ($this->handler)(new IngredientUpdateCommand(
            id: $ingredientId->toString(),
            name: "test",
            description: "tastes yummy",
            ingredientTypeId: $ingredientTypeId
        ));
    }
}
