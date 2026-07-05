<?php
namespace App\MealCourse\Domain\Model;

use App\MealCourse\Domain\Exceptions\MealCourseEmptyNameException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use DateTimeImmutable;

final class MealCourse extends AggregateRoot
{
    /**
     * @throws MealCourseEmptyNameException
     */
    private function __construct(
        private readonly AggregateRootId $id,
        private string $name,
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt
    ){
        $this->validate();
    }

    /**
     * @throws MealCourseEmptyNameException
     */
    public function validate(): void
    {
        if (empty($this->name)) {
            throw new MealCourseEmptyNameException();
        }
    }

    /**
     * @throws MealCourseEmptyNameException
     */
    public static function create(string $name): self
    {
        return new self(
            AggregateRootId::generateId(),
            $name,
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
        return $this->name;
    }

    /**
     * @throws MealCourseEmptyNameException
     */
    public function rename(string $name): void
    {
        if (empty(trim($name)))
        {
            throw new MealCourseEmptyNameException();
        }

        $this->name = $name;
    }
}
