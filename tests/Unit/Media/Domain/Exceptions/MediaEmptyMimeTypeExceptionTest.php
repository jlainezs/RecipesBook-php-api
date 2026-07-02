<?php

namespace App\Tests\Unit\Media\Domain\Exceptions;

use App\Media\Domain\Exceptions\MediaEmptyMimeTypeException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MediaEmptyMimeTypeExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $exception = new MediaEmptyMimeTypeException();
        $this->assertSame('Media mime type cannot be empty', $exception->getMessage());
    }
}
