<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Query\Season\GetSeasons\GetSeasonsDto;
use App\Season\Application\Query\Season\GetSeasons\GetSeasonsQueryFactory;
use App\Season\Presentation\Http\Response\SeasonsListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class GetSeasonsController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetSeasonsQueryFactory $queryFactory
    ){}

    #[Route('/seasons', name: 'seasons_list', methods: ['GET'])]
    public function __invoke(#[MapQueryString] GetSeasonsDto $dto): JsonResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return SeasonsListJsonResponse::create($response->items);
    }
}
