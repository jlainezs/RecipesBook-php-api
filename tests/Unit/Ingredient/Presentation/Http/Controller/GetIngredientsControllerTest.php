<?php

namespace App\Tests\Unit\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Query\Ingredient\GetIngredients\GetIngredientsQuery;
use App\Ingredient\Application\Query\Ingredient\GetIngredients\GetIngredientsQueryDto;
use App\Ingredient\Application\Query\Ingredient\GetIngredients\GetIngredientsQueryResponse;
use App\Ingredient\Presentation\Http\Controller\GetIngredientsController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetIngredientsControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private ApplicationDataValidator $validator;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    #[Test]
    public function test_it_validates_dispatches_command_and_returns_200_response(): void
    {
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function(GetIngredientsQuery $query) {
                    return $query->offset >= 0
                        && $query->limit > 0;
                }
            ))
            ->willReturn(new GetIngredientsQueryResponse([]));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->isInstanceOf(GetIngredientsQuery::class));

        $controller = new GetIngredientsController($this->queryBus, $this->validator);
        $request = new GetIngredientsQueryDto(0, 20);
        $response = $controller($request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
