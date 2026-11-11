<?php

namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesQuery;
use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesDto;
use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesQueryFactory;
use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesQueryResponse;
use App\Recipe\Application\Query\Recipe\RecipeDto;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Presentation\Http\Controller\GetRecipesController;
use App\Recipe\Presentation\Http\Resource\RecipeResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Query\PaginatedList\PageDto;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $request = new GetRecipesDto(
            new PageDto(
                offset: 0,
                limit: 20
            )
        );
        $recipe = new RecipeDto(
            id: AggregateRootId::generateId()->toString(),
            name: 'test',
            servings: 4,
            rating: 5,
            description: 'test',
            source: 'test',
            steps: [],
            ingredients: [],
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
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
                new GetRecipesQuery($request->page->offset, $request->page->limit)
            );

        $controller = new GetRecipesController($this->queryBus, $this->queryFactory, new RecipeResource($this->createStub(UrlGeneratorInterface::class)));
        $response = $controller($request);
        $document = json_decode($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $document->data);
    }
}
