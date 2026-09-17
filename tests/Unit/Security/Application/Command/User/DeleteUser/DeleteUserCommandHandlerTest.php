<?php
namespace App\Tests\Unit\Security\Application\Command\User\DeleteUser;

use App\Security\Application\Command\User\DeleteUser\DeleteUserCommand;
use App\Security\Application\Command\User\DeleteUser\DeleteUserCommandHandler;
use App\Security\Domain\Exceptions\UserNotFoundException;
use App\Security\Domain\Model\User;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DeleteUserCommandHandlerTest extends TestCase
{
    private UserRepositoryInterface $repository;
    private DeleteUserCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->handler = new DeleteUserCommandHandler($this->repository);
    }

    #[Test]
    public function it_deletes_the_user(): void
    {
        $user = User::create(
            'eml@example.com',
            'password',
            'First',
            'Last',
            []
        );
        $id = $user->getId();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($user);

        $this->repository
            ->expects($this->once())
            ->method('delete')
            ->with($user);

        ($this->handler)(new DeleteUserCommand($id->toString()));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_the_user_is_not_found(): void
    {
        $id = AggregateRootId::generateId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $this->repository
            ->expects($this->never())
            ->method("delete");

        $this->expectException(UserNotFoundException::class);
        ($this->handler)(new DeleteUserCommand($id->toString()));
    }
}
