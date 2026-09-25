<?php
namespace App\Tests\Unit\Security\Application\Command\User\CreateUser;

use App\Security\Application\Command\User\CreateUser\CreateUserCommand;
use App\Security\Application\Command\User\CreateUser\CreateUserCommandHandler;
use App\Security\Application\Service\ApplicationPasswordHasher;
use App\Security\Domain\Model\User;
use App\Security\Domain\Model\UserRole;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserCommandHandlerTest extends TestCase
{
    private UserRepositoryInterface&MockObject $repository;
    private CreateUserCommandHandler $handler;
    private ApplicationPasswordHasher $passwordHasher;
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->userPasswordHasherInterface = $this->createMock(UserPasswordHasherInterface::class);
        $this->passwordHasher = new ApplicationPasswordHasher($this->userPasswordHasherInterface);
        $this->handler = new CreateUserCommandHandler($this->repository, $this->passwordHasher);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
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
            [UserRole::USER]
        ));
    }
}
