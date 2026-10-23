<?php
namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeCommandFactory;
use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeDto;
use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeResponse;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CreateIngredientTypeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CreateIngredientTypeCommandFactory $commandFactory,
        private readonly UrlGeneratorInterface $urlGenerator,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/ingredient-types', name: 'create_ingredient_type', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateIngredientTypeDto $dto):JsonResponse
    {
        $cmd = $this->commandFactory->createFromDto($dto);
        $this->commandBus->dispatch($cmd);
        $response = new CreateIngredientTypeResponse($cmd->id);

        return new JsonResponse(
            data: $response,
            status: Response::HTTP_CREATED,
            headers: [
                'Content-Type' => 'application/json',
                'Location' => $this->urlGenerator->generate('get_ingredient_type', ['id' => $cmd->id], UrlGeneratorInterface::ABSOLUTE_URL),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
