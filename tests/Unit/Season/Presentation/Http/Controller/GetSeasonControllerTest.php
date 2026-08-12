<?php
namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Query\Season\SeasonsQuery;
use App\Season\Domain\Model\Season;
use App\Season\Presentation\Http\Controller\GetSeasonController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\TestCase;

class GetSeasonControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $season = Season::create('test');

        $queryBus->expects($this->once())
            ->method('ask')
            ->with(this->callback(
                fn(SeasonsQuery $query) => $query->id !== $season->getId()->toString()
            ))->willReturn($season);
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn(SeasonsQuery $query) => $query->id !== $season->getId()->toString()
            ));
        $controller = new GetSeasonController($queryBus, $validator);
        $request = Request::create(
            uri: '/api/v1/seasons/' . $season->getId()->toString(),
            server: ['Content-Type' => 'application/json']
        );
        $request->attributes->add(['id' => $season->getId()->toString()]);

        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
