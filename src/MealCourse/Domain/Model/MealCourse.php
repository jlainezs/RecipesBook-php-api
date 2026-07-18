<?php
namespace App\MealCourse\Domain\Model;

use App\MealCourse\Domain\Exceptions\MealCourseEmptyNameException;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\RequiredName;
use DateTimeImmutable;

final class MealCourse extends AggregateRoot
{
    /**
     * @throws MealCourseEmptyNameException
     */
    private function __construct(
        private readonly AggregateRootId $id,
        private RequiredName $name,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws MealCourseEmptyNameException
     * @throws EmptyIdNotAllowedException
     */
    public static function create(string $name): self
    {
        return new self(
            AggregateRootId::generateId(),
            new RequiredName($name),
            new DateTimeImmutable(),
            new DateTimeImmutable()
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

    public function getName(): string
    {
        return $this->name->value();
    }

    /**
     * @throws MealCourseEmptyNameException
     */
    public function rename(string $name): void
    {
        $this->name = new RequiredName($name);
    }
}
