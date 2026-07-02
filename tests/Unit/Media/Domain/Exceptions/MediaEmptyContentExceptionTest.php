<?php

namespace App\Tests\Unit\Media\Domain\Exceptions;

use App\Media\Domain\Exceptions\MediaEmptyContentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MediaEmptyContentExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $exception = new MediaEmptyContentException();
        $this->assertSame('Media content cannot be empty', $exception->getMessage());
    }
}
