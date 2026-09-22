<?php
namespace App\Tests\Unit\Security\Application\Command\User\UpdateUser;
use App\Security\Application\Command\User\UpdateUser\UpdateUserCommand;
use App\Security\Application\Command\User\UpdateUser\UpdateUserCommandHandler;
use App\Security\Application\Service\ApplicationPasswordHasher;
use App\Security\Domain\Exceptions\UserNotFoundException;
use App\Security\Domain\Model\User;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUserCommandHandlerTest extends TestCase
{
    private UserRepositoryInterface $repository;
    private UpdateUserCommandHandler $handler;
    private ApplicationPasswordHasher $passwordHasher;
    private UserPasswordHasherInterface $userPasswordHasherInterface;


    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->userPasswordHasherInterface = $this->createMock(UserPasswordHasherInterface::class);
        $this->passwordHasher = new ApplicationPasswordHasher($this->userPasswordHasherInterface);
        $this->handler = new UpdateUserCommandHandler($this->repository, $this->passwordHasher);
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws UserNotFoundException
     */
    #[Test]
    public function it_creates_an_user(): void
    {
        $user = User::create(
            'eml@eml.com',
            'password',
            'first',
            'last',
            []
        );
        $id = $user->getId();

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($user);

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($this->callback(
                fn(AggregateRootId $userId) => $userId->toString() === $id->toString()
            ))->willReturn($user);

        ($this->handler)(new UpdateUserCommand(
            $id->toString(),
            'eml@eml1.com',
            'password2',
                    'firstUpdated',
                    'lastUpdated',
                []
        ));
        $this->assertEquals('eml@eml1.com', $user->getEmail());
        $this->assertEquals('firstUpdated', $user->getFirstName()->value());
        $this->assertEquals('lastUpdated', $user->getLastName()->value());
        $this->toString('password2', $user->getPassword());
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws UserNotFoundException
     */
    #[Test]
    public function it_does_not_change_password_if_it_is_not_provided(): void
    {
        $user = User::create(
            'eml@eml.com',
            'password',
            'first',
            'last',
            []
        );
        $id = $user->getId();

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($user);

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($this->callback(
                fn(AggregateRootId $userId) => $userId->toString() === $id->toString()
            ))->willReturn($user);

        ($this->handler)(new UpdateUserCommand(
            $id->toString(),
            'eml@eml1.com',
            null,
            'firstUpdated',
            'lastUpdated',
            []
        ));
        $this->assertEquals('eml@eml1.com', $user->getEmail());
        $this->assertEquals('firstUpdated', $user->getFirstName()->value());
        $this->assertEquals('lastUpdated', $user->getLastName()->value());
        $this->toString('password', $user->getPassword());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_user_is_not_found(): void
    {
        $id = '3fa85f64-5717-4562-b3fc-2c963f66afa6';
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(UserNotFoundException::class);
        ($this->handler)(new UpdateUserCommand(
            $id,
            'eml@eml.com',
            'password',
                    'first',
                    'last',
                []
        ));
    }
}
