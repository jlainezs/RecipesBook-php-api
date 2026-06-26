<?php

namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Query\Season\SeasonsQuery;
use App\Season\Presentation\Http\Response\SeasonsListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class SeasonsListController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    #[Route('/seasons', name: 'seasons_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $response = $this->queryBus->ask(new SeasonsQuery(
            offset: $request->query->getInt('offset'),
            limit: $request->query->getInt('limit'),
        ));
        return SeasonsListJsonResponse::create($response->items);
    }
}
