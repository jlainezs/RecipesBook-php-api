<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\RecipeUpdate\UpdateRecipeCommandFactory;
use App\Recipe\Application\Command\RecipeUpdate\UpdateRecipeRouteDto;
use App\Recipe\Application\Command\RecipeUpdate\UpdateRecipeDto;
use App\Recipe\Presentation\Http\Resource\RecipeResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateRecipeController extends AbstractController
{
    function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateRecipeCommandFactory $commandFactory
    ){}

    #[Route('/api/v1/recipes/{id}', name: 'update_recipe', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['PATCH'])]
    public function __invoke(
        #[MapRouteParameter]
        UpdateRecipeRouteDto $routeId,
        #[MapJsonApiPayload(RecipeResource::TYPE)]
        UpdateRecipeDto $request
    ): Response
    {
        $cmd = $this->commandFactory->createFromRequestDto($routeId, $request);
        $this->commandBus->dispatch($cmd);

        return new Response(status:Response::HTTP_NO_CONTENT);
    }
}
