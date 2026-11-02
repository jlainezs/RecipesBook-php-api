<?php
namespace App\Shared\Application\Query\PaginatedList;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * JSON:API pagination parameters: ?page[offset]=0&page[limit]=20
 */
readonly class PageDto
{
    public function __construct(
        #[Assert\GreaterThanOrEqual(0)]
        public int $offset = 0,

        #[Assert\GreaterThanOrEqual(0)]
        #[Assert\LessThanOrEqual(100)]
        public int $limit = 20
    ){}
}
