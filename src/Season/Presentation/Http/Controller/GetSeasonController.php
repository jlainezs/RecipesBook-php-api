<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Query\Season\GetSeason\GetSeasonDto;
use App\Season\Application\Query\Season\GetSeason\GetSeasonQueryFactory;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetSeasonController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetSeasonQueryFactory $queryFactory,
    ){}

    #[Route('/api/v1/seasons/{id}', name: 'seasons_get_instance', methods: ['GET'])]
    public function __invoke(#[MapRouteParameter] GetSeasonDto $request):JsonResponse
    {
        $query = $this->queryFactory->createFromDto($request);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
