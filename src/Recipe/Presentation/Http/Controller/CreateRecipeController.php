<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\CreateRecipe\CreateRecipeResponse;
use App\Recipe\Application\Command\CreateRecipe\CreateRecipeCommandFactory;
use App\Recipe\Application\Command\CreateRecipe\CreateRecipeDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
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
        private readonly UrlGeneratorInterface      $urlGenerator
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/recipes', name: 'post_recipe', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload]
        CreateRecipeDto $request
    ): JsonResponse
    {
        $command = $this->commandFactory->createFromDto($request);
        $this->commandBus->dispatch($command);
        $result = new CreateRecipeResponse($command->id);

        return new JsonResponse(
            $result,
            Response::HTTP_CREATED,
            [
                'Content-Type' => 'application/json',
                'Location' => $this->urlGenerator->generate(
                    'get_recipe',
                    ['id' => $command->id],
                    UrlGeneratorInterface::ABSOLUTE_URL),
                'Cache-Control' => 'must-revalidate, no-cache, no-store, private',
            ]
        );
    }
}
