<?php
namespace App\Media\Domain\Model;
use App\Media\Domain\Exceptions\MediaEmptyContentException;
use App\Media\Domain\Exceptions\MediaEmptyMimeTypeException;
use App\Media\Domain\Exceptions\MediaEmptyOwnerClassException;
use App\Media\Domain\Exceptions\MediaEmptyUriException;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use DateTimeImmutable;

final class Media extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private string $mimeType,
        private string $uri,
        private string $content,
        private string $ownerClass,
        private AggregateRootId $ownerId,
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     * @throws MediaEmptyMimeTypeException
     * @throws MediaEmptyUriException
     * @throws MediaEmptyOwnerClassException
     */
    public static function create(
        string $mimeType,
        string $uri,
        string $content,
        string $ownerClass,
        AggregateRootId $ownerId
    ): Media
    {
        if (empty(trim($mimeType))) {
            throw new MediaEmptyMimeTypeException();
        }

        if (empty(trim($uri))){
            throw new MediaEmptyUriException();
        }

        if (empty(trim($ownerClass))) {
            throw new MediaEmptyOwnerClassException();
        }

        return new self(
            id: AggregateRootId::generateId(),
            mimeType: $mimeType,
            uri: $uri,
            content: $content,
            ownerClass: $ownerClass,
            ownerId: $ownerId,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getOwnerClass(): string
    {
        return $this->ownerClass;
    }

    public function setOwner(string $owner): void
    {
        if (empty(trim($owner))) {
            throw new MediaEmptyOwnerClassException();
        }

        $this->ownerClass = $owner;
    }

    public function getOwnerId(): AggregateRootId
    {
        return $this->ownerId;
    }

    public function getOwner(): Owner
    {
        return new Owner(
            ownerId: $this->ownerId,
            ownerClass: $this->ownerClass,
        );
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): void
    {
        if (empty(trim($uri))) {
            throw new MediaEmptyUriException();
        }

        $this->uri = $uri;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @throws MediaEmptyContentException
     */
    public function setContent(string $content): void
    {
        if (empty(trim($content)))
        {
            throw new MediaEmptyContentException();
        }

        $this->content = $content;
    }
}
