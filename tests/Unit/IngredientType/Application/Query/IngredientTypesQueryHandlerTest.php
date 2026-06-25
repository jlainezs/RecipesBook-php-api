<?php

namespace App\Tests\Unit\IngredientType\Application\Query;

use App\IngredientType\Application\Query\IngredientType\IngredientTypesQuery;
use App\IngredientType\Application\Query\IngredientType\IngredientTypesQueryHandler;
use App\IngredientType\Application\Query\IngredientType\IngredientTypesQueryResponse;
use App\IngredientType\Application\Service\IngredientTypeItemsPager;
use App\IngredientType\Domain\Model\IngredientType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IngredientTypesQueryHandlerTest extends TestCase
{
    private IngredientTypeItemsPager&MockObject $pager;
    private IngredientTypesQueryHandler $handler;

    protected function setUp(): void
    {
        $this->pager = $this->createMock(IngredientTypeItemsPager::class);
        $this->handler = new IngredientTypesQueryHandler($this->pager);
    }

    #[Test]
    public function it_returns_a_response_with_mapped_dtos(): void
    {
        $vegetable = IngredientType::create('Vegetable');
        $fruit = IngredientType::create('Fruit');

        $this->pager
            ->method('items')
            ->with(0, 20)
            ->willReturn([$vegetable, $fruit]);

        $response = ($this->handler)(new IngredientTypesQuery(0, 20));

        $this->assertInstanceOf(IngredientTypesQueryResponse::class, $response);
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

        $response = ($this->handler)(new IngredientTypesQuery());

        $this->assertInstanceOf(IngredientTypesQueryResponse::class, $response);
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

        ($this->handler)(new IngredientTypesQuery(10, 5));
    }
}
