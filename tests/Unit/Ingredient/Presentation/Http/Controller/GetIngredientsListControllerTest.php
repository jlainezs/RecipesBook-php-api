<?php

namespace App\Tests\Unit\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Query\Ingredient\IngredientsQuery;
use App\Ingredient\Application\Query\Ingredient\IngredientsQueryResponse;
use App\Ingredient\Presentation\Http\Controller\GetIngredientsListController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class GetIngredientsListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private ApplicationDataValidator $validator;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    public function test_it_validates_dispatches_command_and_returns_200_response(): void
    {
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function(IngredientsQuery $query) {
                    return $query->offset >= 0
                        && $query->limit > 0;
                }
            ))
            ->willReturn(new IngredientsQueryResponse([]));
        $controller = new GetIngredientsListController($this->queryBus);
        $request = Request::Create(
            uri: '/api/v1/ingredients?offset=0&limit=10',
            server: ['CONTENT_TYPE' => 'application/json']
        );
        $response = $controller($request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
