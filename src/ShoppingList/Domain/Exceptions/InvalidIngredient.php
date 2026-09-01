<?php
namespace App\ShoppingList\Domain\Exceptions;

use App\Shared\Domain\ValueObjects\AggregateRootId;
use InvalidArgumentException;
use Throwable;

class InvalidIngredient extends InvalidArgumentException
{
    public function __construct(AggregateRootId $ingredientId, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf("Invalid ingredient '%s'", $$ingredientId->toString());
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
