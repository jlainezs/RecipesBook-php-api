<?php
namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\RecipeInstanceQuery;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Presentation\Http\Controller\GetRecipeController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class GetRecipeControllerTest extends TestCase
{
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $recipe = Recipe::create(
            name: 'test',
            servings: 4,
            rating: 5,
            description: 'test',
            source: 'test',
            steps: [],
            ingredients: []
        );
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (RecipeInstanceQuery $command) => $command->id === $recipe->getId()->toString()
            ));
        $queryBus->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn (RecipeInstanceQuery $command) => $command->id === $recipe->getId()->toString()
            ));
        $controller = new GetRecipeController($queryBus, $validator);
        $request = Request::create(
            uri: '/api/v1//recipes/' . $recipe->getId()->toString(),
            server: ['Content-Type' => 'application/json']
        );
        $request->attributes->set('id', $recipe->getId()->toString());

        $response = $controller($request);
        
        $this->assertEquals(200, $response->getStatusCode());
    }
}
