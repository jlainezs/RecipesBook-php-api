<?php
namespace App\Tests\Unit\Security\Application\Query\User\GetUser;

use App\Security\Application\Query\User\GetUser\GetUserQuery;
use App\Security\Application\Query\User\GetUser\GetUserQueryHandler;
use App\Security\Domain\Exceptions\UserNotFoundException;
use App\Security\Domain\Model\User;
use App\Security\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetUserQueryHandlerTest extends TestCase
{
    private UserRepositoryInterface&MockObject $repository;
    private GetUserQueryHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->handler = new GetUserQueryHandler($this->repository);
    }

    /**
     * @throws UserNotFoundException
     */
    #[Test]
    public function it_returns_a_response_with_dto_when_found(): void
    {
        $user = User::create(
            'test@example.com',
            'password',
            'Test',
            'User',
            []
        );
        $id = $user->getId()->toString();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($user);
        $response = ($this->handler)(new GetUserQuery($id));

        $this->assertNotNull($response->user);
        $this->assertEquals($id, $response->user->id);
        $this->assertEquals('test@example.com', $response->user->email);
        $this->assertEquals('Test', $response->user->firstName);
        $this->assertEquals('User', $response->user->lastName);
    }

    #[Test]
    public function it_throws_when_user_is_not_found(): void
    {
        $id = '3fa85f64-5717-4562-b3fc-2c963f66afa6';

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);
        ($this->handler)(new GetUserQuery($id));
    }
}
