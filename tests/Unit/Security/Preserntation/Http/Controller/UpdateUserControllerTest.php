<?php
namespace App\Tests\Unit\Security\Preserntation\Http\Controller;

use App\Security\Application\Command\User\UpdateUser\UpdateUserCommand;
use App\Security\Application\Command\User\UpdateUser\UpdateUserDto;
use App\Security\Domain\Model\User;
use App\Security\Domain\Model\UserRole;
use App\Security\Presentation\Http\Controller\UpdateUserController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UpdateUserControllerTest extends TestCase
{
    #[Test]
    public function it_dispatches_command_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $user = User::create(
            'eml@eml.com',
            'password',
            'first',
            'last',
            [UserRole::USER]
        );
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (UpdateUserCommand $cmd) => $cmd->id === $user->getId()->toString()
            ));
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (UpdateUserCommand $cmd) => $cmd->id === $user->getId()->toString()
            ));
        $controller = new UpdateUserController($commandBus, $validator);
        $request = new UpdateUserDto(
            'eml1@eml.com',
            null,
            "First updated",
            "Last updated",
            [UserRole::USER]
        );
        $response = $controller($user->getId()->toString(), $request);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
