<?php
namespace App\ShoppingList\Domain\Exceptions;

use App\Shared\Domain\ValueObjects\AggregateRootId;
use InvalidArgumentException;
use Throwable;

class InvalidUnitOfMeasure extends InvalidArgumentException
{
    public function __construct(AggregateRootId $uomId, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf("Invalid unit of measure '%s'", $$uomId->toString());
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
