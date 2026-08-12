<?php
namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Query\Season\SeasonsQuery;
use App\Season\Application\Query\Season\SeasonsQueryResponse;
use App\Season\Presentation\Http\Controller\SeasonsListController;
use App\Shared\Application\Bus\QueryBus;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class SeasonsListControllerTest extends TestCase
{
    private QueryBus $queryBus;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    #[Test]
    public function it_returns_meal_course_list(): void
    {
        $this->queryBus->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn (SeasonsQuery $query) => ($query->offset >= 0) && ($query->limit >= 0)
            ))
            ->willReturn(new SeasonsQueryResponse([]));
        $controller = new SeasonsListController($this->queryBus);
        $request = Request::create(
            uri: '/api/v1/seasons?offset=0&limit=10',
            server: ['Content-Type' => 'application/json']
        );

        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
