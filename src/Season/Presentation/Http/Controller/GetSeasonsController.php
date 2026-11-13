<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Query\Season\GetSeasons\GetSeasonsDto;
use App\Season\Application\Query\Season\GetSeasons\GetSeasonsQueryFactory;
use App\Season\Presentation\Http\Resource\SeasonResource;
use App\Season\Presentation\Http\Response\SeasonsListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class GetSeasonsController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetSeasonsQueryFactory $queryFactory,
        private readonly SeasonResource $resource
    ){}

    #[Route('/seasons', name: 'seasons_list', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        GetSeasonsDto $dto
    ): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse($this->resource->collection($response->items, $query->offset, $query->limit));
    }
}
