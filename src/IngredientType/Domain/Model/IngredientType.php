<?php
namespace App\IngredientType\Domain\Model;

use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;

class IngredientType extends AggregateRoot
{
    public function __construct(
        public readonly AggregateRootId $id,
        public string $name
    ){}
}
