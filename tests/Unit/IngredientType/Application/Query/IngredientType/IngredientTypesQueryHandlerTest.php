<?php

namespace App\Tests\Unit\IngredientType\Application\Query\IngredientType;

use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQuery;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQueryHandler;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQueryResponse;
use App\IngredientType\Application\Service\IngredientTypeItemsPager;
use App\IngredientType\Domain\Model\IngredientType;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[AllowMockObjectsWithoutExpectations]
class IngredientTypesQueryHandlerTest extends TestCase
{
    private IngredientTypeItemsPager&MockObject $pager;
    private GetIngredientTypesQueryHandler $handler;

    protected function setUp(): void
    {
        $this->pager = $this->createMock(IngredientTypeItemsPager::class);
        $this->handler = new GetIngredientTypesQueryHandler($this->pager);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_returns_a_response_with_mapped_dtos(): void
    {
        $vegetable = IngredientType::create('Vegetable');
        $fruit = IngredientType::create('Fruit');

        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(0, 20)
            ->willReturn([$vegetable, $fruit]);

        $response = ($this->handler)(new GetIngredientTypesQuery(0, 20));

        $this->assertInstanceOf(GetIngredientTypesQueryResponse::class, $response);
        $this->assertCount(2, $response->items);
        $this->assertSame($vegetable->getId()->toString(), $response->items[0]->id);
        $this->assertSame('Vegetable', $response->items[0]->name);
        $this->assertSame($fruit->getId()->toString(), $response->items[1]->id);
        $this->assertSame('Fruit', $response->items[1]->name);
    }

    #[Test]
    public function it_returns_an_empty_response_when_no_items_exist(): void
    {
        $this->pager
            ->method('items')
            ->willReturn([]);

        $response = ($this->handler)(new GetIngredientTypesQuery());

        $this->assertInstanceOf(GetIngredientTypesQueryResponse::class, $response);
        $this->assertEmpty($response->items);
    }

    #[Test]
    public function it_forwards_offset_and_limit_to_the_pager(): void
    {
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(10, 5)
            ->willReturn([]);

        ($this->handler)(new GetIngredientTypesQuery(10, 5));
    }
}
