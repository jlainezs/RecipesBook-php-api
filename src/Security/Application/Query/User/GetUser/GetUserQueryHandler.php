<?php
namespace App\Security\Application\Query\User\GetUser;

use App\Security\Application\Query\User\UserDto;
use App\Security\Domain\Exceptions\UserNotFoundException;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetUserQueryHandler
{
    public function __construct(private UserRepositoryInterface $repository)
    {}

    public function __invoke(GetUserQuery $query): GetUserQueryResponse
    {
        if ($user = $this->repository->findOne(new AggregateRootId($query->id)))
        {
            return new GetUserQueryResponse(
                new UserDto(
                    id: $user->getId()->toString(),
                    email: $user->getEmail(),
                    firstName: $user->getFirstName()->value(),
                    lastName: $user->getLastName()->value(),
                    roles:$user->getRoles(),
                    createdAt: $user->getCreatedAt(),
                    updatedAt: $user->getUpdatedAt()
                )
            );
        }

        throw new UserNotFoundException($query->id);
    }
}
