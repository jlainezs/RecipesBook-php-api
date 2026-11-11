<?php
namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\GetRecipe\GetRecipeDto;
use App\Recipe\Application\Query\Recipe\GetRecipe\GetRecipeQuery;
use App\Recipe\Application\Query\Recipe\GetRecipe\GetRecipeQueryFactory;
use App\Recipe\Application\Query\Recipe\GetRecipe\GetRecipeQueryResponse;
use App\Recipe\Application\Query\Recipe\RecipeDto;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Presentation\Http\Controller\GetRecipeController;
use App\Recipe\Presentation\Http\Resource\RecipeResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetRecipeControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $queryFactory = $this->createMock(GetRecipeQueryFactory::class);
        $resource = new RecipeResource($this->createStub(UrlGeneratorInterface::class));

        $id = AggregateRootId::generateId();
        $query = new GetRecipeQuery($id->toString());
        $request = new GetRecipeDto($id->toString());

        $recipe = new RecipeDto(
            id: $id->toString(),
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

        $queryFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn($query);
        $queryBus->expects($this->once())
            ->method('ask')
            ->with($query)
            ->willReturn(new GetRecipeQueryResponse($recipe) );
        $controller = new GetRecipeController($queryBus, $queryFactory, $resource);
        $response = $controller(new GetRecipeDto($recipe->id));

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
