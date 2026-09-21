<?php
namespace App\Tests\Unit\Security\Application\Query\User\GetUsers;

use App\Security\Application\Query\User\GetUsers\GetUsersQuery;
use App\Security\Application\Query\User\GetUsers\GetUsersQueryHandler;
use App\Security\Application\Service\UserItemsPager;
use App\Security\Domain\Model\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

class GetUsersQueryHandlerTest extends TestCase
{
    private UserItemsPager&Stub $pager;
    private GetUsersQueryHandler $handler;

    public function setUp(): void
    {
        $this->pager = $this->createMock(UserItemsPager::class);
        $this->handler = new GetUsersQueryHandler($this->pager);
    }

    #[Test]
    public function it_returns_a_response_with_mapped_dtos(): void
    {
        $user1 = User::create(
            email: 'eml1@eml.com',
            password: 'password1',
            firstName: 'firstName1',
            lastName: 'lastName1',
            roles: []
        );
        $user2 = User::create(
            email: 'eml2@eml.com',
            password: 'password2',
            firstName: 'firstName2',
            lastName: 'lastName2',
            roles: []
        );

        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(0, 20)
            ->willReturn([$user1, $user2]);

        $response = ($this->handler)(new GetUsersQuery(0, 20));

        $this->assertCount(2, $response->items);
        $this->assertEquals($user1->getId()->toString(), $response->items[0]->id);
        $this->assertEquals($user2->getId()->toString(), $response->items[1]->id);
    }

    #[Test]
    public function it_returns_an_empty_response_when_no_items_exist(): void
    {
        $this->pager
            ->method('items')
            ->willReturn([]);

        $response = ($this->handler)(new GetUsersQuery(0, 20));

        $this->assertEmpty($response->items);
    }

    #[Test]
    public function it_forwards_offset_and_limit_to_the_pager(): void
    {
        $this->pager
            ->expects($this->once())
            ->method('items')
            ->with(10, 5)
            ->willReturn([]);

        ($this->handler)(new GetUsersQuery(10, 5));
    }
}
