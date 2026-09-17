<?php
namespace App\Security\Application\Command\User\DeleteUser;

use App\Security\Domain\Exceptions\UserNotFoundException;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $repository
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     * @throws UserNotFoundException
     */
    public function __invoke(DeleteUserCommand $command): void
    {
        $user = $this->repository->findOne(new AggregateRootId($command->id));

        if ($user)
        {
            $this->repository->delete($user);
        }
        else
        {
            throw new UserNotFoundException($command->id);
        }
    }
}
