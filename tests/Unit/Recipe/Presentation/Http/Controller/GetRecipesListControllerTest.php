<?php

namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\RecipesQuery;
use App\Recipe\Application\Query\Recipe\RecipesQueryResponse;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Presentation\Http\Controller\GetRecipesListController;
use App\Recipe\Presentation\Http\Response\RecipesListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class GetRecipesListControllerTest extends TestCase
{
    private QueryBus $queryBus;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_returns_recipes_list(): void
    {
        $recipe = Recipe::create(
            name: 'test',
            servings: 4,
            rating: 5,
            description: 'test',
            source: 'test',
            steps: [],
            ingredients: []
        );
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->withAnyParameters()
            ->willReturn(new RecipesQueryResponse([$recipe]));
        $controller = new GetRecipesListController($this->queryBus);
        $request = Request::create(
            uri: '/api/v1/recipes?offset=0&limit=10',
            server: ['Content-Type' => 'application/json']
        );
        $request->attributes->add(['offset' => 0, 'limit' => 10]);

        $response = $controller($request);
        $data = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $data->items);
    }
}
