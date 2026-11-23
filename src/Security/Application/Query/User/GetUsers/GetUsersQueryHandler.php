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
            fn(User $user) => UserDto::fromDomain($user),
            $this->list->items($query->offset, $query->limit)
        );

        return new GetUsersQueryResponse($itemsDto);
    }
}
