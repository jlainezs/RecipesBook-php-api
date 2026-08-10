<?php
namespace App\Tests\Unit\Recipe\Application\Query\Recipe;

use App\Recipe\Application\Query\Recipe\RecipesQuery;
use App\Recipe\Application\Query\Recipe\RecipesQueryHandler;
use App\Recipe\Application\Service\RecipeItemsPager;
use App\Recipe\Domain\Model\Recipe;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RecipesQueryHandlerTest extends TestCase
{
    private RecipeItemsPager&MockObject $pager;
    private RecipesQueryHandler $handler;
    private Recipe $testRecipe;

    protected function setUp(): void
    {
        $this->pager = $this->createMock(RecipeItemsPager::class);
        $this->handler = new RecipesQueryHandler($this->pager);
        $this->testRecipe = Recipe::create(
            name: 'Test Recipe',
            servings: 1,
            rating: 5,
            description: 'Test Description',
            source: 'https://test.com',
            steps: [],
            ingredients: []
        );
    }

    #[Test]
    public function it_returns_a_response_with_mapped_dtos(): void
    {
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(0, 20)
            ->willReturn([$this->testRecipe]);
        $response = ($this->handler)(new RecipesQuery(0, 20));

        $this->assertCount(1, $response->items);
        $this->assertSame($this->testRecipe->getName(), $response->items[0]->name);
        $this->assertSame($this->testRecipe->getServings()->value(), $response->items[0]->servings);
        $this->assertSame($this->testRecipe->getRating()->value(), $response->items[0]->rating);
        $this->assertSame($this->testRecipe->getDescription(), $response->items[0]->description);
        $this->assertSame($this->testRecipe->getSource(), $response->items[0]->source);
    }

    #[Test]
    public function it_returns_an_empty_response_when_no_items_are_found(): void
    {
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->withAnyParameters()
            ->willReturn([]);

        $response = ($this->handler)(new RecipesQuery(0, 20));
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

        $response = ($this->handler)(new RecipesQuery(10, 5));
    }
}
