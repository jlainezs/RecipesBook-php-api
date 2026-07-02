<?php

namespace App\Tests\Unit\Media\Domain\Exceptions;

use App\Media\Domain\Exceptions\MediaInvalidOwnerIdException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MediaInvalidOwnerIdExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $exception = new MediaInvalidOwnerIdException();
        $this->assertSame('Provided media owner id is not a valid value', $exception->getMessage());
    }
}
