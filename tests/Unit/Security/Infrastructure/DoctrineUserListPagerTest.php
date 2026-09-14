<?php

namespace App\Tests\Unit\Security\Infrastructure;

use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Security\Infrastructure\DoctrineUsersListPager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DoctrineUserListPagerTest extends TestCase
{
    private DoctrineUsersListPager $pager;
    private UserRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->pager = new DoctrineUsersListPager($this->repository);
    }

    #[Test]
    public function it_should_return_an_empty_list_when_there_are_no_seasons()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $result = $this->pager->items(1, 10);
        $this->assertCount(0, $result);
    }
}
