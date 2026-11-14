<?php
namespace App\Security\Presentation\Http\Resource;

use App\Security\Application\Query\User\UserDto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class UserResource
{
    public const string TYPE = 'user';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {}

    public function selfUrl(string $id): string
    {
        return $this->urlGenerator->generate('users_get_instance', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function listUrl(int $offset, int $limit): string
    {
        return $this->urlGenerator->generate('users_list', ['offset' => $offset, 'limit' => $limit], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function resourceObject(UserDto $userDto): array
    {
        return [
            'type' => self::TYPE,
            'id' => $userDto->id,
            'attributes' => [
                'firstName' => $userDto->firstName,
                'lastName' => $userDto->lastName,
                'roles' => $userDto->roles,
                'email' => $userDto->email,
                'createdAt' => $userDto->createdAt,
                'updatedAt' => $userDto->updatedAt,
            ],
            'links' => [
                'self' => $this->selfUrl($userDto->id),
            ]
        ];
    }

    public function collection(array $items, int $offset, int $limit): array
    {
        return [
            'links' => [
                'self' => $this->listUrl($offset, $limit),
                'first' => $this->listUrl(0, $limit),
                'prev' => $offset >0 ? $this->listUrl(max(0, $offset - $limit), $limit) : null,
                'next' => $limit > 0 && count($items) === $limit ? $this->listUrl($offset + $limit, $limit) : null,
            ],
            'data' => array_map($this->resourceObject(...), $items),
        ];
    }

    public function item(UserDto $userDto): array
    {
        return [
            'links' => [
                'self' => $this->selfUrl($userDto->id),
            ],
            'data' => $this->resourceObject($userDto),
        ];

    }

    public function itemReference(string $id): array
    {
        return [
            'links' => [
                'self' => $this->selfUrl($id),
            ],
            'data' => [
                'type' => self::TYPE,
                'id' => $id,
                'links' => [
                    'self' => $this->selfUrl($id),
                ],
                'attributes' => [
                    'id' => $id,
                ],
            ],
        ];

    }
}
