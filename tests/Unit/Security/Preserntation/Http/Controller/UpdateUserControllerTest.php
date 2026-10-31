<?php
namespace App\Tests\Unit\Security\Preserntation\Http\Controller;

use App\Security\Application\Command\User\UpdateUser\UpdateUserCommand;
use App\Security\Application\Command\User\UpdateUser\UpdateUserCommandFactory;
use App\Security\Application\Command\User\UpdateUser\UpdateUserDto;
use App\Security\Application\Command\User\UpdateUser\UpdateUserRouteDto;
use App\Security\Domain\Model\User;
use App\Security\Domain\Model\UserRole;
use App\Security\Presentation\Http\Controller\UpdateUserController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory = $this->createMock(UpdateUserCommandFactory::class);
        $user = User::create(
            AggregateRootId::generateId()->toString(),
            'eml@eml.com',
            'password',
            'first',
            'last',
            [UserRole::USER]
        );

        $requestId = new UpdateUserRouteDto($user->getId()->toString());
        $request = new UpdateUserDto(
            'eml1@eml.com',
            null,
            "First updated",
            "Last updated",
            [UserRole::USER->value]
        );


        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (UpdateUserCommand $cmd) => $cmd->id === $user->getId()->toString()
            ));
        $cmdFactory->expects($this->once())
            ->method('createFromRequestDto')
            ->withAnyParameters()
            ->willReturn(
                new UpdateUserCommand(
                    $requestId->id,
                    $request->email,
                    null,
                    $request->firstName,
                    $request->lastName,
                    $request->roles
                )
            );

        $controller = new UpdateUserController($commandBus, $cmdFactory);
        $response = $controller($requestId, $request);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
