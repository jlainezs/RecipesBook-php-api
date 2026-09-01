<?php
namespace App\ShoppingList\Domain\Exceptions;

use App\Shared\Domain\Exceptions\EntityNotFoundException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Throwable;

final class ShoppingListNotFoundException extends EntityNotFoundException
{
    public function __construct(AggregateRootId $id, int $code = 0, ?Throwable $previous = null)
    {
        $msg = sprintf("Shopping list with id '%s' not found", $id->toString());
        parent::__construct($msg, $code, $previous);
    }
}
