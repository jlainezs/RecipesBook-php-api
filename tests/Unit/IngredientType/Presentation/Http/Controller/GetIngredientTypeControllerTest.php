<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeDto;
use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeQuery;
use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeQueryFactory;
use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeResponse;
use App\IngredientType\Application\Query\IngredientType\IngredientTypeDto;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Presentation\Http\Controller\GetIngredientTypeController;
use App\IngredientType\Presentation\Http\Resource\IngredientTypeResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
            ->withAnyParameters()
            ->willReturn(new GetIngredientTypeResponse(new IngredientTypeDto(
                $ingredientType->getId()->toString(),
                $ingredientType->getName()->value(),
                $ingredientType->getCreatedAt(),
                $ingredientType->getUpdatedAt()
            )));
        $controller = new GetIngredientTypeController($queryBus,
            $queryFactory,
            new IngredientTypeResource($this->createStub(UrlGeneratorInterface::class))
        );

        $request = new GetIngredientTypeDto($ingredientType->getId()->toString());
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
