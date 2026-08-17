<?php

namespace App\ShoppingList\Domain\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidShoppingListItemQuantity extends InvalidArgumentException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            empty($message)
                ? 'Shopping list item quantity is invalid'
                : $message,
            $code,
            $previous
        );
    }
}
