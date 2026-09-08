<?php
namespace App\Season\Application\Query\Season\GetSeasons;

use App\Season\Application\Query\Season\SeasonDto;
use App\Season\Application\Service\SeasonItemsPager;
use App\Season\Domain\Model\Season;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetSeasonsQueryHandler
{
    public function __construct(private SeasonItemsPager $list)
    {}

    public function __invoke(GetSeasonsQuery $query): GetSeasonsQueryResponse
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
        return new GetSeasonsQueryResponse($itemsDto);
    }
}
