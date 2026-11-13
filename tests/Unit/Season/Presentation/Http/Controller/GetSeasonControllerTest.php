<?php
namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Query\Season\GetSeason\GetSeasonDto;
use App\Season\Application\Query\Season\GetSeason\GetSeasonQuery;
use App\Season\Application\Query\Season\GetSeason\GetSeasonQueryFactory;
use App\Season\Application\Query\Season\GetSeason\GetSeasonQueryResponse;
use App\Season\Application\Query\Season\SeasonDto;
use App\Season\Domain\Model\Season;
use App\Season\Presentation\Http\Controller\GetSeasonController;
use App\Season\Presentation\Http\Resource\SeasonResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetSeasonControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $queryFactory = $this->createMock(GetSeasonQueryFactory::class);
        $season = Season::create('test');

        $queryBus->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn(GetSeasonQuery $query) => $query->id === $season->getId()->toString()
            ))->willReturn(new GetSeasonQueryResponse(
                new SeasonDto(
                    id: $season->getId()->toString(),
                    name: $season->getName(),
                    createdAt: $season->getCreatedAt(),
                    updatedAt: $season->getUpdatedAt(),
                )
            ));
        $queryFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new GetSeasonQuery($season->getId()->toString()));
        $controller = new GetSeasonController(
            $queryBus,
            $queryFactory,
            new SeasonResource($this->createStub(UrlGeneratorInterface::class))
        );
        $request = new GetSeasonDto($season->getId()->toString());
        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
