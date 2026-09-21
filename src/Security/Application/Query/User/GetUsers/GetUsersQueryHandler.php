<?php
namespace App\Security\Application\Query\User\GetUsers;

use App\Security\Application\Query\User\UserDto;
use App\Security\Application\Service\UserItemsPager;
use App\Security\Domain\Model\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetUsersQueryHandler
{
    public function __construct(private UserItemsPager $list)
    {}

    public function __invoke(GetUsersQuery $query): GetUsersQueryResponse
    {
        $itemsDto = array_map(
            fn(User $user) => new UserDto(
                $user->getId()->toString(),
                $user->getEmail(),
                $user->getFirstName()->value(),
                $user->getLastName()->value(),
                $user->getRoles(),
                $user->getCreatedAt(),
                $user->getUpdatedAt(),
            ),
            $this->list->items($query->offset, $query->limit)
        );

        return new GetUsersQueryResponse($itemsDto);
    }
}
