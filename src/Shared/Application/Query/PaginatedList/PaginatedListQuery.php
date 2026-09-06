<?php

namespace App\Shared\Application\Query\PaginatedList;

use Symfony\Component\Validator\Constraints as Assert;

readonly class PaginatedListQuery
{
    public function __construct(
        #[Assert\GreaterThanOrEqual(0)]
        public int $offset = 0,

        #[Assert\GreaterThanOrEqual(0)]
        #[Assert\LessThanOrEqual(100)]
        public int $limit = 20
    ){}
}
