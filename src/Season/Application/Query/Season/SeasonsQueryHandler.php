<?php
namespace App\Season\Application\Query\Season;

use App\Season\Application\Service\SeasonItemsPager;
use App\Season\Domain\Model\Season;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SeasonsQueryHandler
{
    public function __construct(private SeasonItemsPager $list)
    {}

    public function __invoke(SeasonsQuery $query): SeasonsQueryResponse
    {
        $itemsDto = array_map(
         fn(Season $t) => new SeasonDto(
                $t->getId()->toString(),
                $t->getName(),
                $t->getCreatedAt(),
                $t->getUpdatedAt()
            ),
            $this->list->items($query->offset, $query->limit)
        );
        return new SeasonsQueryResponse($itemsDto);
    }
}
