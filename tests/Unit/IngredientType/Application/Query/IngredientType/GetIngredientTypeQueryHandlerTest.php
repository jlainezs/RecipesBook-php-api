<?php

namespace App\Tests\Unit\IngredientType\Application\Query\IngredientType;

use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeQuery;
use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeQueryHandler;
use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeResponse;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetIngredientTypeQueryHandlerTest extends TestCase
{
    private IngredientTypeRepositoryInterface&MockObject $repository;
    private GetIngredientTypeQueryHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IngredientTypeRepositoryInterface::class);
        $this->handler = new GetIngredientTypeQueryHandler($this->repository);
    }

    #[Test]
    public function it_returns_a_response_with_dto_when_found(): void
    {
        $ingredientType = IngredientType::create('Vegetable');
        $id = $ingredientType->getId()->toString();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($ingredientType);

        $response = ($this->handler)(new GetIngredientTypeQuery($id));

        $this->assertInstanceOf(GetIngredientTypeResponse::class, $response);
        $this->assertNotNull($response->ingredientType);
        $this->assertSame($id, $response->ingredientType->id);
        $this->assertSame('Vegetable', $response->ingredientType->name);
    }

    #[Test]
    public function it_throws_when_ingredient_type_is_not_found(): void
    {
        $id = '3fa85f64-5717-4562-b3fc-2c963f66afa6';

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $this->expectException(IngredientTypeNotFoundException::class);

        ($this->handler)(new GetIngredientTypeQuery($id));
    }
}
