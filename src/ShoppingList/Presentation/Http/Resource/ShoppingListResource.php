<?php
namespace App\ShoppingList\Presentation\Http\Resource;

use App\ShoppingList\Application\Query\GetShoppingList\ShoppingListDto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class ShoppingListResource
{
    public const string TYPE = 'shopping-list';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {}

    public function item(ShoppingListDto $shoppingListDto): array
    {
        return [
            'links' => ['self' => $this->selfUrl($shoppingListDto->id)],
            'data' => $this->resourceObject($shoppingListDto)
        ];
    }

    /**
     * @param ShoppingListDto[] $items
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function collection(array $items, int $offset, int $limit): array
    {
        return [
            'links' => [
                'self' => $this->listUrl($offset, $limit),
                'first' => $this->listUrl(0, $limit),
                'prev' => $offset > 0 ? $this->listUrl(max(0, $offset - $limit), $limit) : null,
                'next' => $limit > 0 && count($items) === $limit ? $this->listUrl($offset + $limit, $limit) : null,
            ],
            'data' => array_map($this->resourceObject(...), $items),
        ];
    }

    public function selfUrl(string $id): string
    {
        return $this->urlGenerator->generate('get_shopping-list', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function resourceObject(ShoppingListDto $shoppingListDto): array
    {
        return [
            'type' => self::TYPE,
            'id' => $shoppingListDto->id,
            'attributes' => [
                'name' => $shoppingListDto->name,
                'scheduledFor' => $shoppingListDto->scheduledFor,
                'items' => $shoppingListDto->items,
                'createdAt' => $shoppingListDto->createdAt,
                'updatedAt' => $shoppingListDto->updatedAt,
            ],
            'links' => ['self' => $this->selfUrl($shoppingListDto->id)],
        ];
    }

    public function listUrl(int $offset, int $limit): string
    {
        return $this->urlGenerator->generate(
            'get_shopping-lists_list',
            ['page' => ['offset' => $offset, 'limit' => $limit]],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    public function itemReference(string $id): array
    {
        return [
            'links' => ['self' => $this->selfUrl($id)],
            'data' => [
                'type' => self::TYPE,
                'id' => $id,
                'links' => ['self' => $this->selfUrl($id)],
                'attributes' => [
                    'id' => $id,
                ]
            ],
        ];
    }
}
