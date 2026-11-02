<?php
namespace App\UnitOfMeasure\Presentation\Http\Resource;

use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureDto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class UnitOfMeasureResource
{
    public const string TYPE = 'units-of-measure';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {}

    public function item(UnitOfMeasureDto $uom): array
    {
        return [
            'links' => ['self' => $this->selfUrl($uom->id)],
            'data' => $this->resourceObject($uom),
        ];
    }

    /**
     * @param UnitOfMeasureDto[] $items
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
        return $this->urlGenerator->generate('unit_of_measure_get_instance', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    private function resourceObject(UnitOfMeasureDto $uom): array
    {
        return [
            'type' => self::TYPE,
            'id' => $uom->id,
            'attributes' => [
                'name' => $uom->name,
                'symbol' => $uom->symbol,
                'unitOfMeasureType' => $uom->uomType,
                'createdAt' => $uom->createdAt->format(DATE_ATOM),
                'updatedAt' => $uom->updatedAt->format(DATE_ATOM),
            ],
            'links' => ['self' => $this->selfUrl($uom->id)],
        ];
    }

    private function listUrl(int $offset, int $limit): string
    {
        return $this->urlGenerator->generate(
            'unit_of_measure_list',
            ['page' => ['offset' => $offset, 'limit' => $limit]],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
