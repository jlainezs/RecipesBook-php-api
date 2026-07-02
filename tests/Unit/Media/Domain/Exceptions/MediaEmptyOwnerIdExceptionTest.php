<?php
namespace App\Tests\Unit\Media\Domain\Exceptions;

use App\Media\Domain\Exceptions\MediaEmptyOwnerIdException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MediaEmptyOwnerIdExceptionTest extends TestCase
{
    #[Test]
    public function it_have_a_descriptive_message(): void
    {
        $exception = new MediaEmptyOwnerIdException();
        $this->assertSame('Media owner id cannot be empty', $exception->getMessage());
    }
}
