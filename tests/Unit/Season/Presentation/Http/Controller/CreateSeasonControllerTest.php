<?php
namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\SeasonCreateCommand;
use App\Season\Domain\Model\Season;
use App\Season\Presentation\Http\Controller\PostSeasonController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
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
                fn (SeasonCreateCommand $cmd) => $cmd->name === $season->getName()
            ));
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (SeasonCreateCommand $cmd) => $cmd->name === $season->getName()
            ));
        $controller = new PostSeasonController($commandBus, $validator);
        $request = Request::create(
            uri:'/api/v1/seasons/create',
            method:'POST',
            server: ['Content-Type' => 'application/json'],
            content: json_encode(['name' => $season->getName()])
        );

        $response = $controller($request);

        $this->assertEquals(201, $response->getStatusCode());
    }
}
