<?php
namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\CreateSeason\CreateSeasonCommand;
use App\Season\Application\Command\Season\CreateSeason\CreateSeasonCommandFactory;
use App\Season\Application\Command\Season\CreateSeason\CreateSeasonDto;
use App\Season\Presentation\Http\Controller\CreateSeasonController;
use App\Season\Presentation\Http\Resource\SeasonResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateSeasonControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $createCmdFactory = $this->createMock(CreateSeasonCommandFactory::class);
        $resource = new SeasonResource($this->createStub(UrlGeneratorInterface::class));

        $id = AggregateRootId::generateId()->toString();

        $request = new CreateSeasonDto(
            name:'test'
        );
        $createCmdFactory->expects($this->once())
            ->method('createFromDto')
            ->with($request)
            ->willReturn(new CreateSeasonCommand(
                    $id, $request->name
                )
            );

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (CreateSeasonCommand $cmd) => $cmd->name === $request->name
            ));

        $controller = new CreateSeasonController($commandBus, $createCmdFactory, $resource);
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
