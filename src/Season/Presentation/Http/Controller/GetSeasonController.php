<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Query\Season\GetSeason\GetSeasonDto;
use App\Season\Application\Query\Season\GetSeason\GetSeasonQueryFactory;
use App\Season\Presentation\Http\Resource\SeasonResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class GetSeasonController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetSeasonQueryFactory $queryFactory,
        private readonly SeasonResource $resource
    ){}

    #[Route('/api/v1/seasons/{id}', name: 'seasons_get_instance', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function __invoke(#[MapRouteParameter] GetSeasonDto $request):JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($request);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse($this->resource->item($response->season));
    }
}
