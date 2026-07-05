<?php

namespace App\Tests\Unit\IngredientType\Application\Query;

use App\IngredientType\Application\Query\IngredientType\IngredientTypeInstanceQueryHandler;
use App\IngredientType\Application\Query\IngredientType\IngredientTypeInstanceQuery;
use App\IngredientType\Application\Query\IngredientType\IngredientTypeInstanceResponse;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IngredientTypeInstanceHandlerTest extends TestCase
{
    private IngredientTypeRepositoryInterface&MockObject $repository;
    private IngredientTypeInstanceQueryHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IngredientTypeRepositoryInterface::class);
        $this->handler = new IngredientTypeInstanceQueryHandler($this->repository);
    }

    #[Test]
    public function it_returns_a_response_with_dto_when_found(): void
    {
        $ingredientType = IngredientType::create('Vegetable');
        $id = $ingredientType->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($ingredientType);

        $response = ($this->handler)(new IngredientTypeInstanceQuery($id));

        $this->assertInstanceOf(IngredientTypeInstanceResponse::class, $response);
        $this->assertNotNull($response->ingredientType);
        $this->assertSame($id, $response->ingredientType->id);
        $this->assertSame('Vegetable', $response->ingredientType->name);
    }

    #[Test]
    public function it_throws_when_ingredient_type_is_not_found(): void
    {
        $id = '3fa85f64-5717-4562-b3fc-2c963f66afa6';

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $this->expectException(IngredientTypeNotFoundException::class);

        ($this->handler)(new IngredientTypeInstanceQuery($id));
    }
}
