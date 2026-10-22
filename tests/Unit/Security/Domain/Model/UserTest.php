<?php
namespace App\Tests\Unit\Security\Domain\Model;

use App\Security\Domain\Model\User;
use App\Security\Domain\Model\UserRole;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\Exceptions\EmptyRequiredNameException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_user(): void
    {
        $user = User::create(
            AggregateRootId::generateId()->toString(),
            'email@email.com',
            'XXXXX',
            "User",
            "Last",
            [],
        );
        $this->assertEquals('email@email.com', $user->getEmail()->value());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertEquals($user->getEmail()->value(), $user->getUserIdentifier());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $user1 = User::create(
            AggregateRootId::generateId()->toString(),
            'email@email.com',
            'XXXXX',
            "User",
            "Last",
            [],
        );
        $user2 = User::create(
            AggregateRootId::generateId()->toString(),
            'email@XXXXX.com',
            'XXXXX',
            "User222",
            "Last333",
            [],
        );
        $this->assertNotEquals($user1->getUserIdentifier(), $user2->getUserIdentifier());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider("invalidNames")]
    public function it_throws_on_invalid_name(string $value): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        User::create(
            AggregateRootId::generateId()->toString(),
            'email@XXXXX.com',
            'XXXXX',
            $value,
            "Last333",
            [],
        );
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider("invalidNames")]
    public function it_throws_on_invalid_last_name(string $value): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        User::create(
            AggregateRootId::generateId()->toString(),
            'email@XXXXX.com',
            'XXXXX',
            "Name",
            $value,
            [],
        );
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_contains_Role_User()
    {
        $user = User::create(
            AggregateRootId::generateId()->toString(),
            'email@XXXXX.com',
            'XXXXX',
            "John",
            "Doe",
            [],
        );
        $this->assertContains(UserRole::USER->value, $user->getRoles());
    }

    public static function invalidNames(): iterable
    {
        yield 'an empty string' => [''];
        yield 'a string with only spaces' => ['   '];
    }
}
