<?php
namespace App\Media\Domain\Model;

use App\Media\Domain\Exceptions\MediaEmptyMimeTypeException;
use App\Media\Domain\Exceptions\MediaEmptyOwnerClassException;
use App\Media\Domain\Exceptions\MediaEmptyPathException;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\Model\Owner;
use App\Shared\Domain\ValueObject\AggregateRootId;
use DateTimeImmutable;

final class Media extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId   $id,
        private string $ownerClass,
        private AggregateRootId $ownerClassId,
        private string $mimeType,
        private string $path,
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     * @throws MediaEmptyMimeTypeException
     * @throws MediaEmptyPathException
     * @throws MediaEmptyOwnerClassException
     */
    public static function create(
        string $mimeType,
        string $path,
        string $content,
        string $ownerClass,
        AggregateRootId $ownerId
    ): Media
    {
        if (empty(trim($mimeType))) {
            throw new MediaEmptyMimeTypeException();
        }

        if (empty(trim($path))){
            throw new MediaEmptyPathException();
        }

        if (empty(trim($ownerClass))) {
            throw new MediaEmptyOwnerClassException();
        }

        return new self(
            id: AggregateRootId::generateId(),
            ownerClass: $ownerClass,
            ownerClassId: $ownerId,
            mimeType: $mimeType,
            path: $path,
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

    public function getOwnerClassId(): AggregateRootId
    {
        return $this->ownerClassId;
    }

    public function getOwner(): Owner
    {
        return new Owner(
            ownerId: $this->ownerClassId,
            ownerClass: $this->ownerClass,
        );
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @throws MediaEmptyMimeTypeException
     */
    public function setMimeType(string $mimeType): void
    {
        if (empty(trim($mimeType))) {
            throw new MediaEmptyMimeTypeException();
        }

        $this->mimeType = $mimeType;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @throws MediaEmptyPathException
     */
    public function setPath(string $path): void
    {
        if (empty(trim($path))) {
            throw new MediaEmptyPathException();
        }

        $this->path = $path;
    }
}
