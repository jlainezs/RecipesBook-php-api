<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQuery;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQueryDto;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQueryResponse;
use App\IngredientType\Presentation\Http\Controller\GetIngredientTypesController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class IngredientTypesListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private ApplicationDataValidator $validator;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
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
        $controller = new GetIngredientTypesController($this->queryBus, $this->validator);
        $request = new GetIngredientTypesQueryDto(0, 10);

        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
