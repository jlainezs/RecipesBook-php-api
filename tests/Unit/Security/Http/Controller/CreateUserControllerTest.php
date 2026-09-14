<?php
namespace App\Tests\Unit\Security\Http\Controller;

use App\Security\Application\Command\User\CreateUser\CreateUserCommand;
use App\Security\Application\Command\User\CreateUser\CreateUserDto;
use App\Security\Presentation\Http\Controller\CreateUserController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CreateUserControllerTest extends TestCase
{
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $userDto = new CreateUserDto(
            'eml@eml.com',
            'password',
            'John',
            'Doe'
        );
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn(CreateUserCommand $cmd) => $cmd->email === $userDto->email
            ));
        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn(CreateUserCommand $cmd) => $cmd->email === $userDto->email
            ));
        $controller = new CreateUserController($commandBus, $validator);
        $response = $controller($userDto);
        $this->assertEquals(201, $response->getStatusCode());
    }
}
