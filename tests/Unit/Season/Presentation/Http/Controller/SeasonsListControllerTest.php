<?php
namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Query\Season\GetSeasons\GetSeasonsDto;
use App\Season\Application\Query\Season\GetSeasons\GetSeasonsQuery;
use App\Season\Application\Query\Season\GetSeasons\GetSeasonsQueryFactory;
use App\Season\Application\Query\Season\GetSeasons\GetSeasonsQueryResponse;
use App\Season\Presentation\Http\Controller\GetSeasonsController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SeasonsListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private GetSeasonsQueryFactory $queryFactory;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->queryFactory = $this->createMock(GetSeasonsQueryFactory::class);
    }

    #[Test]
    public function it_returns_seasons_list(): void
    {
        $request = new GetSeasonsDto(offset: 0, limit: 10);
        $this->queryBus->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn (GetSeasonsQuery $query) => ($query->offset >= 0) && ($query->limit >= 0)
            ))
            ->willReturn(new GetSeasonsQueryResponse([]));
        $this->queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new GetSeasonsQuery($request->offset, $request->limit));

        $controller = new GetSeasonsController($this->queryBus, $this->queryFactory);
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
