<?php
namespace App\Tests\Unit\Season\Domain\Exceptions;

use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SeasonNotFoundExceptionTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $id = AggregateRootId::generateId();
        $message = sprintf("Season with id '%s' not found", $id->toString());
        $exception = new SeasonNotFoundException($id->toString());
        $this->assertEquals($message, $exception->getMessage());
    }
}
