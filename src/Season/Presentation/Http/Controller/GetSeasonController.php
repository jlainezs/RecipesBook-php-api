<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Query\Season\SeasonInstanceQuery;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class GetSeasonController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator,
    ){}

    #[Route('/api/v1/seasons/{id}', name: 'seasons_get_instance', methods: ['GET'])]
    public function __invoke(Request $request):JsonResponse
    {
        $id = $request->attributes->getString('id');
        $query = new SeasonInstanceQuery($id);
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
