<?php
namespace App\Tests\Unit\Ingredient\Application\Query\GetIngredient;

use App\Ingredient\Application\Query\Ingredient\GetIngredient\GetIngredientQuery;
use App\Ingredient\Application\Query\Ingredient\GetIngredient\GetIngredientQueryHandler;
use App\Ingredient\Application\Query\Ingredient\GetIngredient\GetIngredientResponse;
use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetIngredientQueryHandlerTest extends TestCase
{
    private IngredientRepositoryInterface $repository;
    private GetIngredientQueryHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IngredientRepositoryInterface::class);
        $this->handler = new GetIngredientQueryHandler($this->repository);
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws IngredientNotFoundException
     */
    #[Test]
    public function it_should_return_ingredient_instance(): void
    {
        $ingredientTypeId = "b4bac84a-9193-4405-98b7-7e53a65cc9a8";
        $ingredientTypeRef = new IngredientTypeReference($ingredientTypeId);

        $ingredient = Ingredient::create('test', 'description', $ingredientTypeRef);
        $id = $ingredient->getId();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($ingredient);

        $queryResult = $this->handler->__invoke(new GetIngredientQuery($id));
        $this->assertNotNull($queryResult);
        $this->assertInstanceOf(GetIngredientResponse::class, $queryResult);
        $this->assertEquals($id->toString(), $queryResult->ingredientDto->id);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_throw_when_ingredient_not_found():void
    {
        $id = AggregateRootId::generateId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $this->expectException(IngredientNotFoundException::class);
        ($this->handler)(new GetIngredientQuery($id));
    }
}
