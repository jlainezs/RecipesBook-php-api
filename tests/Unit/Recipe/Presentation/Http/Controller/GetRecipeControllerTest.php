<?php
namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\GetRecipe\GetRecipeDto;
use App\Recipe\Application\Query\Recipe\GetRecipe\GetRecipeQuery;
use App\Recipe\Application\Query\Recipe\GetRecipe\GetRecipeQueryFactory;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Presentation\Http\Controller\GetRecipeController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetRecipeControllerTest extends TestCase
{
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $queryFactory = $this->createMock(GetRecipeQueryFactory::class);
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
        $queryFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new GetRecipeQuery($recipe->getId()->toString()));
        $queryBus->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn (GetRecipeQuery $query) => $query->id === $recipe->getId()->toString()
            ));
        $controller = new GetRecipeController($queryBus, $queryFactory);
        $response = $controller(new GetRecipeDto($recipe->getId()->toString()));

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
