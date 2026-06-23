<?php

namespace App\Shared\Application\Query\PaginatedList;

readonly class PaginatedListQuery
{
    public function __construct(
        public int $offset = 0,
        public int $limit = 20
    ){}
}
