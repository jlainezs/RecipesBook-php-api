<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\IngredientTypesQuery;
use App\IngredientType\Application\Query\IngredientType\IngredientTypesQueryResponse;
use App\IngredientType\Presentation\Http\Controller\IngredientTypesListController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class IngredientTypesListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    //private ApplicationDataValidator $validator;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        // TODO: request is not validated!
        // $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    #[Test]
    public function itReturnsIngredientTypesList(): void
    {
        $this->queryBus->
            expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function(IngredientTypesQuery $query) {
                    return $query->offset >= 0
                        && $query->limit > 0;
                }
            ))
            ->willReturn(new IngredientTypesQueryResponse([]));
        $controller = new IngredientTypesListController($this->queryBus, $this->validator);
        $request = Request::create(
            uri: '/api/v1/ingredient-types?offset?&limit=10',
            server: ['Content-Type' => 'application/json']
        );

        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
