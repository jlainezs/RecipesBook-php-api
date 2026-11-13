<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientTypes;

use App\Shared\Application\Query\PaginatedList\PageDto;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetIngredientTypesDto
{
    public function __construct(
        #[Assert\Valid]
        public PageDto $page = new PageDto()
    ){}
}
