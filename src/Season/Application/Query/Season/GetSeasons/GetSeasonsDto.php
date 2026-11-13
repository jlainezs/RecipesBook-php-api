<?php
namespace App\Season\Application\Query\Season\GetSeasons;

use App\Shared\Application\Query\PaginatedList\PageDto;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetSeasonsDto
{
    public function __construct(
        #[Assert\Valid]
        public PageDto $page = new PageDto()
    ){}
}
