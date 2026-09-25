<?php
namespace App\Security\Domain\Model;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\RequiredName;
use DateTimeImmutable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/** https://symfony.com/doc/current/security.html */
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        readonly private AggregateRootId   $id,
        private Email                      $email,
        private string                     $password,
        private RequiredName               $firstName,
        private RequiredName               $lastName,
        private array                      $roles,
        readonly private DateTimeImmutable $createdAt,
        private DateTimeImmutable          $updatedAt,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public static function create(
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        array $roles,
    ):self {
        return new self(
            AggregateRootId::generateId(),
            new Email($email),
            $password,
            new RequiredName($firstName),
            new RequiredName($lastName),
            $roles,
            new DateTimeImmutable(),
            new DateTimeImmutable(),
        );
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        $roles = array_map(
            fn(UserRole $role) => $role->value,
            $this->roles
        );
        $roles[] = UserRole::USER->value;

        return array_unique($roles);
    }

    public function setRoles(array $roles):void
    {
        $this->roles = $roles;
    }

    /**
     * @inheritDoc
     */
    public function getUserIdentifier(): string
    {
        return $this->getEmail()->value();
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function changePassword(string $value): void
    {
        $this->password = $value;
    }

    public function getFirstName(): RequiredName
    {
        return $this->firstName;
    }

    public function changeFirstName(RequiredName $value): void
    {
        $this->firstName = $value;
    }

    public function getLastName(): RequiredName
    {
        return $this->lastName;
    }

    public function changeLastName(RequiredName $value): void
    {
        $this->lastName = $value;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function changeEmail(Email $value): void
    {
        $this->email = $value;
    }

    public function getFullName(): string
    {
        return $this->firstName->value() . ' ' . $this->lastName->value();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
