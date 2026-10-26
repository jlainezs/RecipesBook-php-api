<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeDto;
use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeQuery;
use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeQueryFactory;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Presentation\Http\Controller\GetIngredientTypeController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class GetIngredientTypeControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $queryFactory = $this->createMock(GetIngredientTypeQueryFactory::class);
        $ingredientType = IngredientType::create(AggregateRootId::generateId()->toString(),'test');

        $queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new GetIngredientTypeQuery($ingredientType->getId()->toString()));

        $queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function(GetIngredientTypeQuery $query) use ($ingredientType) {
                    return $query->id === $ingredientType->getId()->toString();
                }
            ))
            ->willReturn($ingredientType);
        $controller = new GetIngredientTypeController($queryBus, $queryFactory);

        $response = $controller(
            new GetIngredientTypeDto($ingredientType->getId()->toString())
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
