<?php
namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Query\Season\GetSeasons\GetSeasonsDto;
use App\Season\Application\Query\Season\GetSeasons\GetSeasonsQuery;
use App\Season\Application\Query\Season\GetSeasons\GetSeasonsQueryResponse;
use App\Season\Presentation\Http\Controller\GetSeasonsController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class SeasonsListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private ApplicationDataValidator $validator;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    #[Test]
    public function it_returns_meal_course_list(): void
    {
        $this->queryBus->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn (GetSeasonsQuery $query) => ($query->offset >= 0) && ($query->limit >= 0)
            ))
            ->willReturn(new GetSeasonsQueryResponse([]));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (GetSeasonsQuery $query): bool {
                    return $query->offset >= 0 && $query->limit > 0;
                }
            ));
        $controller = new GetSeasonsController($this->queryBus, $this->validator);
        $request = new GetSeasonsDto(offset: 0, limit: 10);
        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
