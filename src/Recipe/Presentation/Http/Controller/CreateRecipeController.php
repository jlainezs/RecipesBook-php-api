<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\CreateRecipe\CreateRecipeResponse;
use App\Recipe\Application\Command\CreateRecipe\CreateRecipeCommandFactory;
use App\Recipe\Application\Command\CreateRecipe\CreateRecipeDto;
use App\Recipe\Presentation\Http\Resource\RecipeResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CreateRecipeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus                 $commandBus,
        private readonly CreateRecipeCommandFactory $commandFactory,
        private readonly RecipeResource $resource
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/recipes', name: 'post_recipe', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['POST'])]
    public function __invoke(
        #[MapJsonApiPayload(RecipeResource::TYPE)]
        CreateRecipeDto $request
    ): JsonApiResponse
    {
        $command = $this->commandFactory->createFromDto($request);
        $this->commandBus->dispatch($command);

        return new JsonApiResponse(
            $this->resource->itemReference($command->id),
            Response::HTTP_CREATED,
            headers: [
                'Location' => $this->resource->selfUrl($command->id),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
