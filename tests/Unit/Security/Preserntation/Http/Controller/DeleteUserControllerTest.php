<?php
namespace App\Tests\Unit\Security\Preserntation\Http\Controller;

use App\Security\Application\Command\User\DeleteUser\DeleteUserCommand;
use App\Security\Application\Command\User\DeleteUser\DeleteUserCommandFactory;
use App\Security\Application\Command\User\DeleteUser\DeleteUserDto;
use App\Security\Presentation\Http\Controller\DeleteUserController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DeleteUserControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_the_request_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory = $this->createMock(DeleteUserCommandFactory::class);
        $id = AggregateRootId::generateId()->toString();
        $request = new DeleteUserDto($id);

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();
        $cmdFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new DeleteUserCommand($request->id));
        $controller = new DeleteUserController($commandBus, $cmdFactory);
        $response = $controller($request);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
