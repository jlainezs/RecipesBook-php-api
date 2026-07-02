<?php
namespace App\Tests\Unit\Media\Domain\Exceptions;

use App\Media\Domain\Exceptions\MediaEmptyPathException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MediaEmptyUriExceptionTest extends TestCase
{
    #[Test]
    public function it_have_a_descriptive_message(): void
    {
        $exception = new MediaEmptyPathException();
        $this->assertSame('Media path cannot be empty', $exception->getMessage());
    }
}
