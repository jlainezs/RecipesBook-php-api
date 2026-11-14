<?php
namespace App\Tests\Unit\Security\Preserntation\Http\Controller;

use App\Security\Application\Query\User\GetUsers\GetUsersDto;
use App\Security\Application\Query\User\GetUsers\GetUsersQuery;
use App\Security\Application\Query\User\GetUsers\GetUsersQueryFactory;
use App\Security\Application\Query\User\GetUsers\GetUsersQueryResponse;
use App\Security\Application\Query\User\UserDto;
use App\Security\Domain\Model\User;
use App\Security\Presentation\Http\Controller\GetUsersController;
use App\Security\Presentation\Http\Resource\UserResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Query\PaginatedList\PageDto;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetUsersControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private GetUsersQueryFactory $queryFactory;
    private UserResource $resource;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->queryFactory = $this->createMock(GetUsersQueryFactory::class);
        $this->resource = new UserResource($this->createStub(UrlGeneratorInterface::class));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_returns_users_list(): void
    {
        $user1 = User::create(
            AggregateRootId::generateId()->toString(),
            'eml1@eml.com',
            'password1',
            'first name 1',
            'last name 1',
            []
        );
        $user2 = User::create(
            AggregateRootId::generateId()->toString(),
            'eml2@eml.com',
            'password2',
            'first name2',
            'last name2',
            []
        );
        $request = new GetUsersDto(
            new PageDto(0, 20),
        );
        $this->queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(
                new GetUsersQuery($request->page->offset, $request->page->limit)
            );
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->withAnyParameters()
            ->willReturn(new GetUsersQueryResponse(
                [
                    new UserDto(
                        $user1->getId()->toString(),
                        $user1->getEmail()->value(),
                        $user1->getFirstName()->value(),
                        $user1->getLastName()->value(),
                        $user1->getRoles(),
                        $user1->getCreatedAt(),
                        $user1->getCreatedAt()
                    ),
                    new UserDto(
                        $user2->getId()->toString(),
                        $user2->getEmail()->value(),
                        $user2->getFirstName()->value(),
                        $user2->getLastName()->value(),
                        $user2->getRoles(),
                        $user2->getCreatedAt(),
                        $user2->getCreatedAt()
                    )
                ]
            ));

        $controller = new GetUsersController($this->queryBus, $this->queryFactory, $this->resource);

        $response = $controller($request);
        $data = json_decode($response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(2, $data->data);
    }
}
