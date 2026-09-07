<?php
namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonCommand;
use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonDto;
use App\Season\Domain\Model\Season;
use App\Season\Presentation\Http\Controller\UpdateSeasonController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UpdateSeasonControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $season = Season::create('test');

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (UpdateSeasonCommand $cmd) => $cmd->id === $season->getId()->toString()
            ));
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (UpdateSeasonCommand $cmd) => $cmd->id === $season->getId()->toString()
            ));
        $controller = new UpdateSeasonController($commandBus, $validator);
        $request = new UpdateSeasonDto($season->getName());

        $response = $controller($season->getId()->toString(), $request);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
