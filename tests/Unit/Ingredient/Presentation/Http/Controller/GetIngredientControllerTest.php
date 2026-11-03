<?php
namespace App\Tests\Unit\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Query\Ingredient\GetIngredient\GetIngredientDto;
use App\Ingredient\Application\Query\Ingredient\GetIngredient\GetIngredientQuery;
use App\Ingredient\Application\Query\Ingredient\GetIngredient\GetIngredientQueryFactory;
use App\Ingredient\Application\Query\Ingredient\GetIngredient\GetIngredientResponse;
use App\Ingredient\Application\Query\Ingredient\IngredientDto;
use App\Ingredient\Presentation\Http\Controller\GetIngredientController;
use App\Ingredient\Presentation\Http\Resoource\IngredientResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetIngredientControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private GetIngredientQueryFactory $queryFactory;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->queryFactory = $this->createMock(GetIngredientQueryFactory::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    function it_validates_dispatches_query_and_returns_200(): void
    {
        $ingredient = new IngredientDto(
            '792ffc6a-cc7c-4dfa-8118-24e9bce3409b',
            'test',
            'description',
            AggregateRootId::generateId()->toString(),
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );
        $query = new GetIngredientQuery($ingredient->id);
        $request = new GetIngredientDto($ingredient->id);

        $this->queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn($query);
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($query)
            ->willReturn(new GetIngredientResponse($ingredient));

        $controller = new GetIngredientController(
            $this->queryBus,
            $this->queryFactory,
            new IngredientResource($this->createStub(UrlGeneratorInterface::class))
        );
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $document = json_decode($response->getContent(), true);
        $this->assertSame($ingredient->id, $document['data']['id']);
    }
}
