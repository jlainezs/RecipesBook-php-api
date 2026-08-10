<?php
namespace App\Tests\Unit\Recipe\Application\Query\Recipe;

use App\Recipe\Application\Query\Recipe\RecipeInstanceQuery;
use App\Recipe\Application\Query\Recipe\RecipeInstanceQueryHandler;
use App\Recipe\Domain\Exceptions\RecipeNotFoundException;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RecipeInstanceQueryHandlerTest extends TestCase
{
    private RecipeInstanceQueryHandler $handler;
    private RecipeRepositoryInterface&MockObject $repository;
    private Recipe $testRecipe;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(RecipeRepositoryInterface::class);
        $this->handler = new RecipeInstanceQueryHandler($this->repository);
        $this->testRecipe = Recipe::create(
            name: 'Test Recipe',
            servings: 1,
            rating: 5,
            description: 'Test Description',
            source: 'http://test.com',
            steps: [],
            ingredients: []
        );

    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws RecipeNotFoundException
     */
    #[Test]
    public function it_returns_a_response_with_dto_when_found(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($this->testRecipe->getId()->toString())
            ->willReturn($this->testRecipe);
        $response = ($this->handler)(new RecipeInstanceQuery($this->testRecipe->getId()->toString()));

        $this->assertNotNull($response);
        $this->assertSame($this->testRecipe->getId()->toString(), $response->recipeDto->id);
    }

    #[Test]
    public function it_throws_exception_when_not_found(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($this->testRecipe->getId()->toString())
            ->willReturn(null);
        $this->expectException(RecipeNotFoundException::class);
        ($this->handler)(new RecipeInstanceQuery($this->testRecipe->getId()->toString()));
    }
}
