<?php
namespace App\Tests\Unit\Ingredient\Application\Query\Ingredients;

use App\Ingredient\Application\Query\Ingredient\IngredientsQuery;
use App\Ingredient\Application\Query\Ingredient\IngredientsQueryHandler;
use App\Ingredient\Application\Service\IngredientItemsPager;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IngredientsQueryHandlerTest extends TestCase
{
    private IngredientItemsPager $pager;
    private IngredientsQueryHandler $handler;

    protected function setUp(): void
    {
        $this->pager = $this->createMock(IngredientItemsPager::class);
        $this->handler = new IngredientsQueryHandler($this->pager);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_returns_a_response_with_mapped_dto(): void
    {
        $itr1 = new IngredientTypeReference('c0740d3d-b189-4cf3-90eb-d3dd14f3a4c4');
        $itr2 = new IngredientTypeReference('792ffc6a-cc7c-4dfa-8118-24e9bce3409b');
        $i1 = Ingredient::create('i1', 'd1', $itr1);
        $i2 = Ingredient::create('i2', 'd2', $itr2);

        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(0, 20)
            ->willReturn([$i1, $i2]);

        $response = ($this->handler)(new IngredientsQuery(0, 20));

        $this->assertCount(2, $response->items);
        $this->assertSame($i1->getId()->toString(), $response->items[0]->id);
        $this->assertSame($i2->getId()->toString(), $response->items[1]->id);
        $this->assertSame($i1->getName(), $response->items[0]->name);
        $this->assertSame($i2->getName(), $response->items[1]->name);
        $this->assertSame($i1->getDescription(), $response->items[0]->description);
        $this->assertSame($i2->getDescription(), $response->items[1]->description);
        $this->assertSame($i1->getDescription(), $response->items[0]->description);
        $this->assertSame($i2->getDescription(), $response->items[1]->description);
        $this->assertSame($i1->getIngredientType()->value()->toString(), $response->items[0]->ingredientTypeId);
        $this->assertSame($i2->getIngredientType()->value()->toString(), $response->items[1]->ingredientTypeId);
    }

    #[Test]
    public function it_returns_an_empty_response_when_no_items_exist(): void
    {
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(0, 20)
            ->willReturn([]);

        $response = ($this->handler)(new IngredientsQuery(0, 20));

        $this->assertCount(0, $response->items);
    }

    #[Test]
    public function it_forwards_offset_and_limit_to_the_pager(): void
    {
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(10, 5)
            ->willReturn([]);

        ($this->handler)(new IngredientsQuery(10, 5));
    }
}
