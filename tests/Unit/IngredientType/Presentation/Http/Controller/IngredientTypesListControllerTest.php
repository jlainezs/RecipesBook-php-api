<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQuery;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesDto;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQueryFactory;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQueryResponse;
use App\IngredientType\Presentation\Http\Controller\GetIngredientTypesController;
use App\Shared\Application\Bus\QueryBus;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IngredientTypesListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private GetIngredientTypesQueryFactory $queryFactory;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->queryFactory = $this->createMock(GetIngredientTypesQueryFactory::class);
    }

    #[Test]
    public function itReturnsIngredientTypesList(): void
    {
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
            ->willReturn(new GetIngredientTypesQuery(0, 10));

        $controller = new GetIngredientTypesController($this->queryBus, $this->queryFactory);
        $request = new GetIngredientTypesDto(0, 10);

        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
