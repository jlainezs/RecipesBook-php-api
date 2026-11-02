<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures;

use App\Shared\Application\Query\PaginatedList\PageDto;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetUnitsOfMeasureDto
{
    public function __construct(
        #[Assert\Valid]
        public PageDto $page = new PageDto()
    ){}
}
