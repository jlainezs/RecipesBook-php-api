<?php
namespace App\Security\Application\Command\User\UpdateUser;

use App\Security\Domain\Exceptions\UserNotFoundException;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\RequiredName;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $repository
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     * @throws UserNotFoundException
     */
    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->repository->findOne(new AggregateRootId($command->id));
        if ($user)
        {
            $user->changeEmail(new Email($command->email));
            $user->changeFirstName(new RequiredName($command->firstName));
            $user->changeLastName(new RequiredName($command->lastName));

            if (!empty($command->password))
            {
                $user->changePassword($command->password);
            }

            $this->repository->save($user);
        }
        else
        {
            throw new UserNotFoundException($command->id);
        }
    }
}
