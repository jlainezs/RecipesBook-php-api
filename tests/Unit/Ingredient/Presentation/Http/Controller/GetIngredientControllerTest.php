<?php
namespace App\Tests\Unit\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Query\Ingredient\IngredientInstanceQuery;
use App\Ingredient\Presentation\Http\Controller\GetIngredientController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetIngredientControllerTest extends TestCase
{
    #[Test]
    function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $id = '792ffc6a-cc7c-4dfa-8118-24e9bce3409b';

        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (IngredientInstanceQuery $query) use ($id) {
                    return $query->id === $id;
                }
            ));

        $queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function (IngredientInstanceQuery $query) use ($id) {
                    return $query->id === $id;
                }
            ));

        $controller = new GetIngredientController($queryBus, $validator);

        $response = $controller($id);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
