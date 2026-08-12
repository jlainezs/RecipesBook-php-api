<?php
namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\SeasonUpdateCommand;
use App\Season\Domain\Model\Season;
use App\Season\Presentation\Http\Controller\PutSeasonController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

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
                fn (SeasonUpdateCommand $cmd) => $cmd->id === $season->getId()->toString()
            ));
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (SeasonUpdateCommand $cmd) => $cmd->id === $season->getId()->toString()
            ));
        $controller = new PutSeasonController($commandBus, $validator);
        $payload = ['name' => $season->getName()];
        $request = Request::create(
            uri: '/api/v1/meal-courses/' . $season->getId()->toString(),
            method: 'PUT',
            server: ['Content-Type' => 'application/json'],
            content: json_encode($payload)
        );
        $request->attributes->add(['id' => $season->getId()->toString()]);

        $response = $controller($request);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
