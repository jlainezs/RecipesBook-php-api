<?php
namespace App\Tests\Unit\Ingredient\Application\Command\IngredientCreate;

use App\Ingredient\Application\Command\Ingredient\IngredientCreateCommand;
use App\Ingredient\Application\Command\Ingredient\IngredientCreateCommandHandler;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\IngredientType\Application\Query\IngredientType\FindIngredientTypeReferenceQuery;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IngredientCreateCommandHandlerTest extends TestCase
{
    private IngredientRepositoryInterface $repository;
    private IngredientCreateCommandHandler $handler;
    private QueryBus&MockObject $queryBus;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->repository = $this->createMock(IngredientRepositoryInterface::class);
        $this->handler = new IngredientCreateCommandHandler($this->repository, $this->queryBus);
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws IngredientTypeNotFoundException
     */
    #[Test]
    public function it_creates_and_saves_the_ingredient(): void
    {
        $ingredientTypeId = "b4bac84a-9193-4405-98b7-7e53a65cc9a8";
        $ingredientTypeRef = new IngredientTypeReference($ingredientTypeId);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Ingredient::class));
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->isInstanceOf(FindIngredientTypeReferenceQuery::class))
            ->willReturn($ingredientTypeRef);

        ($this->handler)(new IngredientCreateCommand(
            name: "ingredient 1",
            description: "tastes yummy",
            ingredientTypeId: $ingredientTypeId
            ));
    }

    /**
     * @throws IngredientTypeNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_name_is_empty(): void
    {
        //$ingredient = Ingredient::create("ingredient 1", "tastes yummy");
        $ingredientTypeId = "b4bac84a-9193-4405-98b7-7e53a65cc9a8";
        $ingredientTypeRef = new IngredientTypeReference($ingredientTypeId);
        //$ingredient->changeIngredientType($ingredientTypeRef);
        $this->repository
            ->expects($this->never())
            ->method('save');
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->isInstanceOf(FindIngredientTypeReferenceQuery::class))
            ->willReturn($ingredientTypeRef);
        $this->expectException(EmptyRequiredNameException::class);
        ($this->handler)(new IngredientCreateCommand(
            name: "",
            description: "tastes yummy",
            ingredientTypeId: $ingredientTypeId
            ));
    }
}
