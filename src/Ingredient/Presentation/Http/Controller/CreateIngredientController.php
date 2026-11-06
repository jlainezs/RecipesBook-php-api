<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientCommandFactory;
use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientDto;
use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientResponse;
use App\Ingredient\Presentation\Http\Resoource\IngredientResource;
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

final class CreateIngredientController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CreateIngredientCommandFactory $commandFactory,
        private readonly IngredientResource $resource,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/ingredients', name: 'ingredient_create', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['POST'])]
    public function __invoke(
        #[MapJsonApiPayload(IngredientResource::TYPE)]
        CreateIngredientDto $request
    ): JsonApiResponse
    {
        $cmd = $this->commandFactory->createFromDto($request);
        $this->commandBus->dispatch($cmd);

        return new JsonApiResponse(
            document: $this->resource->itemReference($cmd->id),
            status: Response::HTTP_CREATED,
            headers: [
                'Location' => $this->resource->selfUrl($cmd->id),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ],
        );
    }
}
