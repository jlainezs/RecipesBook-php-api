<?php

namespace App\Tests\Unit\Security\Domain\Exception;

use App\Security\Domain\Exceptions\UserNotFoundException;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UserNotFoundExceptionTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_has_a_descriptive_message()
    {
        $id = AggregateRootId::generateId();
        $message = sprintf("User identified by '%s' not found", $id->toString());
        $exception = new UserNotFoundException($id);
        $this->assertEquals($message, $exception->getMessage());
    }
}
