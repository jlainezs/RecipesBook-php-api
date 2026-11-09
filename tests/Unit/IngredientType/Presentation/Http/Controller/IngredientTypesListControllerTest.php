<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQuery;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesDto;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQueryFactory;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQueryResponse;
use App\IngredientType\Presentation\Http\Controller\GetIngredientTypesController;
use App\IngredientType\Presentation\Http\Resource\IngredientTypeResource;
use App\Shared\Application\Bus\QueryBus;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IngredientTypesListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private GetIngredientTypesQueryFactory $queryFactory;
    private IngredientTypeResource $resource;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->queryFactory = $this->createMock(GetIngredientTypesQueryFactory::class);
        $this->resource = new IngredientTypeResource($this->createStub(UrlGeneratorInterface::class));
    }

    #[Test]
    public function it_returns_an_ingredient_types_list(): void
    {
        $request = new GetIngredientTypesDto(0, 10);
        $this->queryBus->
            expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function(GetIngredientTypesQuery $query) {
                    return $query->offset >= 0
                        && $query->limit > 0;
                }
            ))
            ->willReturn(new GetIngredientTypesQueryResponse([]));
        $this->queryFactory->
            expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new GetIngredientTypesQuery($request->offset, $request->limit));

        $controller = new GetIngredientTypesController($this->queryBus, $this->queryFactory, $this->resource);
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
