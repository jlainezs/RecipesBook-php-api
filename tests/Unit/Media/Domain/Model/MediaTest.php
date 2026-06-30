<?php
namespace App\Tests\Unit\Media\Domain\Model;

use App\Media\Domain\Exceptions\EmptyContentException;
use App\Media\Domain\Exceptions\EmptyMimeTypeException;
use App\Media\Domain\Exceptions\EmptyOwnerClassException;
use App\Media\Domain\Exceptions\EmptyUriException;
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
            "http://example.com",
            "bla bla bla",
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
        $this->expectException(EmptyMimeTypeException::class);
        Media::create(
            "",
            "http://example.com",
            "bla bla bla",
            "A class",
            new AggregateRootId(Uuid::v4()->toString())
        );
    }

    #[Test]
    public function throw_empty_uri(): void
    {
        $this->expectException(EmptyUriException::class);
        Media::create(
            "application/json',",
            "",
            "bla bla bla",
            "A class",
            new AggregateRootId(Uuid::v4()->toString())
        );
    }

    #[Test]
    public function throws_empty_owner_class(): void
    {
        $this->expectException(EmptyOwnerClassException::class);
        Media::create(
            "application/json',",
            "http://example.com",
            "bla bla bla",
            "",
            new AggregateRootId(Uuid::v4()->toString())
        );
    }

    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $first = Media::create(
            "application/json',",
            "http://example.com",
            "bla bla bla",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );
        $second = Media::create(
            "application/json',",
            "http://example.com",
            "bla bla bla",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );
        $this->assertNotSame($first->getId()->toString(), $second->getId()->toString());
    }

    #[Test]
    public function it_setsContent_successfully(): void
    {
        $media = Media::create(
            "application/json',",
            "http://example.com",
            "bla bla bla",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );
        $media->setContent("new content");
        $this->assertSame("new content", $media->getContent());
    }

    #[Test]
    public function throws_empty_content_when_set_blank_content():void
    {
        $media = Media::create(
            "application/json',",
            "http://example.com",
            "bla bla bla",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );
        $this->expectException(EmptyContentException::class);
        $media->setContent("");
    }
    #[Test]
    public function throws_empty_content_when_set_blank_uri():void
    {
        $media = Media::create(
            "application/json',",
            "http://example.com",
            "bla bla bla",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );
        $this->expectException(EmptyUriException::class);
        $media->setUri("");
    }

    #[Test]
    public function throws_empty_owner_when_set_blank_owner():void
    {
        $media = Media::create(
            "application/json',",
            "http://example.com",
            "bla bla bla",
            "AClass",
            new AggregateRootId(Uuid::v4()->toString())
        );
        $this->expectException(EmptyOwnerClassException::class);
        $media->setOwner("");
    }
}
