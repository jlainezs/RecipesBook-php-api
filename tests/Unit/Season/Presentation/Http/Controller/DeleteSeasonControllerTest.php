<?php

namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\DeleteSeason\DeleteSeasonCommand;
use App\Season\Application\Command\Season\DeleteSeason\DeleteSeasonCommandFactory;
use App\Season\Application\Command\Season\DeleteSeason\DeleteSeasonDto;
use App\Season\Presentation\Http\Controller\DeleteSeasonController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteSeasonControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_the_request_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory = $this->createMock(DeleteSeasonCommandFactory::class);
        $id = AggregateRootId::generateId()->toString();
        $request = new DeleteSeasonDto($id);

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();
        $cmdFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new DeleteSeasonCommand($request->id));

        $controller = new DeleteSeasonController($commandBus, $cmdFactory);
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
