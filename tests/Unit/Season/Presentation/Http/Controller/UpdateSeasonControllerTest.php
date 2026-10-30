<?php
namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonCommand;
use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonCommandFactory;
use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonDto;
use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonRouteDto;
use App\Season\Domain\Model\Season;
use App\Season\Presentation\Http\Controller\UpdateSeasonController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateSeasonControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory = $this->createMock(UpdateSeasonCommandFactory::class);
        $season = Season::create('test');
        $requestId = new UpdateSeasonRouteDto($season->getId()->toString());
        $requestData = new UpdateSeasonDto($season->getName());

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (UpdateSeasonCommand $cmd) => $cmd->id === $season->getId()->toString()
            ));
        $cmdFactory->expects($this->once())
            ->method('createFromRequest')
            ->withAnyParameters()
            ->willReturn(new UpdateSeasonCommand($season->getId()->toString(), $season->getName()));
        $controller = new UpdateSeasonController($commandBus, $cmdFactory);

        $response = $controller($requestId, $requestData);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
