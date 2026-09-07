<?php

namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesQuery;
use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesDto;
use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesQueryResponse;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Presentation\Http\Controller\GetRecipesListController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetRecipesListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private ApplicationDataValidator $validator;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_returns_recipes_list(): void
    {
        $recipe = Recipe::create(
            name: 'test',
            servings: 4,
            rating: 5,
            description: 'test',
            source: 'test',
            steps: [],
            ingredients: []
        );
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->withAnyParameters()
            ->willReturn(new GetRecipesQueryResponse([$recipe]));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (GetRecipesQuery $query): bool {
                    return $query->offset >= 0
                        && $query->limit > 10;
                }
            ));

        $controller = new GetRecipesListController($this->queryBus, $this->validator);
        $request = new GetRecipesDto(
            offset: 0,
            limit: 20
        );
        $response = $controller($request);
        $data = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $data->items);
    }
}
