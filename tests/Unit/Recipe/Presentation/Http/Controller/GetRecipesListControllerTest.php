<?php

namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesQuery;
use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesDto;
use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesQueryFactory;
use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesQueryResponse;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Presentation\Http\Controller\GetRecipesListController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class GetRecipesListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private GetRecipesQueryFactory $queryFactory;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->queryFactory = $this->createMock(GetRecipesQueryFactory::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_returns_recipes_list(): void
    {
        $recipe = Recipe::create(
            id: AggregateRootId::generateId()->toString(),
            name: 'test',
            servings: 4,
            rating: 5,
            description: 'test',
            source: 'test',
            steps: [],
            ingredients: []
        );
        $request = new GetRecipesDto(
            offset: 0,
            limit: 20
        );

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->withAnyParameters()
            ->willReturn(new GetRecipesQueryResponse([$recipe]));
        $this->queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(
                new GetRecipesQuery($request->offset, $request->limit)
            );

        $controller = new GetRecipesListController($this->queryBus, $this->queryFactory);
        $response = $controller($request);
        $data = json_decode($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $data->items);
    }
}
