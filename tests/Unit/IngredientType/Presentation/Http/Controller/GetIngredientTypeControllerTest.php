<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\IngredientTypeInstanceQuery;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Presentation\Http\Controller\GetIngredientTypeController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetIngredientTypeControllerTest extends TestCase
{
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $ingredientType = IngredientType::create('test');

        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function(IngredientTypeInstanceQuery $query) use ($ingredientType) {
                    return $query->id === $ingredientType->getId()->toString();
                }
            ));

        $queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function(IngredientTypeInstanceQuery $query) use ($ingredientType) {
                    return $query->id === $ingredientType->getId()->toString();
                }
            ))
            ->willReturn($ingredientType);
        $controller = new GetIngredientTypeController($queryBus, $validator);

        $response = $controller($ingredientType->getId()->toString());

        $this->assertEquals(200, $response->getStatusCode());
    }
}
