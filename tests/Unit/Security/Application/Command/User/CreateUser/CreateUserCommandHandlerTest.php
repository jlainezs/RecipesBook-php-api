<?php
namespace App\Tests\Unit\Security\Application\Command\User\CreateUser;

use App\Security\Application\Command\User\CreateUser\CreateUserCommand;
use App\Security\Application\Command\User\CreateUser\CreateUserCommandHandler;
use App\Security\Domain\Model\User;
use App\Security\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserCommandHandlerTest extends TestCase
{
    private UserRepositoryInterface&MockObject $repository;
    private CreateUserCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->handler = new CreateUserCommandHandler($this->repository);
    }

    #[Test]
    public function it_creates_and_saves_the_user(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(User::class));
        ($this->handler)(new CreateUserCommand(
            "eml@eml.com",
            "password",
            "firstName",
            "lastName",
        ));
    }
}
