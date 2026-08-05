<?php
namespace App\ShoppingList\Domain\Exceptions;

use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use Throwable;

final class ShoppingListNotFoundException extends EntityNotFoundException
{
    public function __construct(AggregateRootId $id, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Shopping list with id {$id} not found", $code, $previous);
    }
}
