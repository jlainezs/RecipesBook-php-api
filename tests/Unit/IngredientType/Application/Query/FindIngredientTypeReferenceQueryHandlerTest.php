<?php

namespace App\Tests\Unit\IngredientType\Application\Query;

use App\IngredientType\Application\Query\IngredientType\FindIngredientTypeReferenceQuery;
use App\IngredientType\Application\Query\IngredientType\FindIngredientTypeReferenceQueryHandler;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FindIngredientTypeReferenceQueryHandlerTest extends TestCase
{
    private IngredientTypeRepositoryInterface $repository;
    private FindIngredientTypeReferenceQueryHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IngredientTypeRepositoryInterface::class);
        $this->handler = new FindIngredientTypeReferenceQueryHandler($this->repository);
    }

    /**
     * @throws IngredientTypeNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_return_ingredient_type_reference(): void
    {
        $ingredientType = IngredientType::create('test');

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($ingredientType->getId())
            ->willReturn($ingredientType);
        $response = ($this->handler)(new FindIngredientTypeReferenceQuery($ingredientType->getId()));

        $this->assertEquals($ingredientType->getId()->toString(), $response);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_ingredient_type_is_not_found(): void
    {
        $ingredientTypeId = AggregateRootId::generateId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($ingredientTypeId)
            ->willReturn(null);

        $this->expectException(IngredientTypeNotFoundException::class);
        ($this->handler)(new FindIngredientTypeReferenceQuery($ingredientTypeId));
    }
}
