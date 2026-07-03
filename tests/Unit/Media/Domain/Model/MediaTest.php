<?php
namespace App\Tests\Unit\Media\Domain\Model;

use App\Media\Domain\Exceptions\MediaEmptyFileNameException;
use App\Media\Domain\Exceptions\MediaEmptyMimeTypeException;
use App\Media\Domain\Exceptions\MediaEmptyOwnerClassException;
use App\Media\Domain\Exceptions\MediaEmptyPathException;
use App\Media\Domain\Model\Media;
use App\Shared\Domain\ValueObject\AggregateRootId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class MediaTest extends TestCase
{
    #[Test]
    public function it_creates_with_a_valid_data(): void
    {
        $media = Media::create(
            "application/json",
            "/some/path/to/a_file.txt",
            "filename.txt",
            "A class",
            new AggregateRootId(Uuid::v4()->toString())
        );

        $this->assertSame("application/json", $media->getMimeType());
        $this->assertInstanceOf(AggregateRootId::class, $media->getId());
        $this->assertInstanceOf(DateTimeImmutable::class, $media->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $media->getUpdatedAt());
    }

    #[Test]
    public function throws_empty_mime_type(): void
    {
        $this->expectException(MediaEmptyMimeTypeException::class);
        Media::create(
            "",
            "/some/path/to/a_file.txt",
            "filename.txt",
            "A class",
            new AggregateRootId(Uuid::v4()->toString())
        );
    }

    #[Test]
    public function throw_empty_path(): void
    {
        $this->expectException(MediaEmptyPathException::class);
        Media::create(
            "application/json',",
            "",
            "filename.txt",
            "A class",
            new AggregateRootId(Uuid::v4()->toString())
        );
    }

    #[Test]
    public function throws_empty_owner_class(): void
    {
        $this->expectException(MediaEmptyOwnerClassException::class);
        Media::create(
            "application/json',",
            "/some/path/to/a_file.txt",
            "filename.txt",
            "",
            new AggregateRootId(Uuid::v4()->toString())
        );
    }

    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $first = Media::create(
            "application/json',",
            "/some/path/to/a_file.txt",
            "filename.txt",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );
        $second = Media::create(
            "application/json',",
            "/some/path/to/a_file.txt",
            "filename.txt",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );
        $this->assertNotSame($first->getId()->toString(), $second->getId()->toString());
    }

    #[Test]
    public function throws_empty_path_when_set_blank_path():void
    {
        $media = Media::create(
            "application/json',",
            "/some/path/tofile",
            "filename.txt",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );
        $this->expectException(MediaEmptyPathException::class);
        $media->setPath("");
    }

    #[Test]
    public function throws_empty_owner_when_set_blank_owner():void
    {
        $media = Media::create(
            "application/json',",
            "/some/path/to/a_file.txt",
            "filename.txt",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );
        $this->expectException(MediaEmptyOwnerClassException::class);
        $media->setOwner("");
    }

    #[Test]
    public function it_keeps_file_name()
    {
        $media = Media::create(
            "application/json',",
            "/some/path/to/a_file.txt",
            "filename.txt",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );

        $this->assertEquals("filename.txt", $media->getFileName());
    }

    #[Test]
    public function throws_empty_file_name_when_using_blank_file_name():void
    {
        $this->expectException(MediaEmptyFileNameException::class);
        $media = Media::create(
            "application/json',",
            "/some/path/to/a_file.txt",
            "",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );
    }

    #[Test]
    public function throws_empty_file_name_when_set_blank_file_name():void
    {
        $media = Media::create(
            "application/json',",
            "/some/path/to/a_file.txt",
            "fileName.txt",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );

        $this->expectException(MediaEmptyFileNameException::class);
        $media->setFileName("");
    }

    #[Test]
    public function it_sets_the_right_owner():void
    {
        $ownerId = new AggregateRootId(Uuid::v4()->toString());
        $media = Media::create(
            "application/json',",
            "/some/path/to/a_file.txt",
            "filename.txt",
            "AClass",
            $ownerId
        );

        $this->assertEquals("AClass", $media->getOwnerClass());
        $this->assertEquals($ownerId, $media->getOwnerClassId());
    }
}
