<?php
namespace App\ShoppingList\Application\Query\GetSoppingLists;

use App\Shared\Application\Query\PaginatedList\PageDto;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetShoppingListsQueryDto
{
    public function __construct(
        #[Assert\Valid]
        public PageDto $page = new PageDto()
    ){}
}
