<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipes;

use App\Shared\Application\Query\PaginatedList\PageDto;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetRecipesDto
{
    public function __construct(
        #[Assert\Valid]
        public PageDto $page = new PageDto()
    ){}
}
