<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientCommandFactory;
use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientDto;
use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientResponse;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
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
        private readonly UrlGeneratorInterface $urlGenerator,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/ingredients', name: 'ingredient_create', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload]
        CreateIngredientDto $request
    ): JsonResponse
    {
        $cmd = $this->commandFactory->createFromDto($request);
        $this->commandBus->dispatch($cmd);
        $response = new CreateIngredientResponse($cmd->id);

        return new JsonResponse(
            data: $response,
            status: Response::HTTP_CREATED,
            headers: [
                'Content-Type' => 'application/json',
                'Location' => $this->urlGenerator->generate('ingredient_get_instance', ['id' => $cmd->id], UrlGeneratorInterface::ABSOLUTE_URL),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ],
        );
    }
}
