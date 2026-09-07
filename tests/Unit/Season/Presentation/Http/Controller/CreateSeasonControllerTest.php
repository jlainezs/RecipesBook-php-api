<?php
namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\CreateSeason\CreateSeasonCommand;
use App\Season\Application\Command\Season\CreateSeason\CreateSeasonDto;
use App\Season\Domain\Model\Season;
use App\Season\Presentation\Http\Controller\CreateSeasonController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateSeasonControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $season = Season::create(name: 'test');

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (CreateSeasonCommand $cmd) => $cmd->name === $season->getName()
            ));
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (CreateSeasonCommand $cmd) => $cmd->name === $season->getName()
            ));
        $controller = new CreateSeasonController($commandBus, $validator);
        $request = new CreateSeasonDto(
            name:$season->getName()
        );
        $response = $controller($request);

        $this->assertEquals(201, $response->getStatusCode());
    }
}
