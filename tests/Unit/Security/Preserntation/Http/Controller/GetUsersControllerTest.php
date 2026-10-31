<?php
namespace App\Tests\Unit\Security\Preserntation\Http\Controller;

use App\Security\Application\Query\User\GetUsers\GetUsersDto;
use App\Security\Application\Query\User\GetUsers\GetUsersQuery;
use App\Security\Application\Query\User\GetUsers\GetUsersQueryFactory;
use App\Security\Application\Query\User\GetUsers\GetUsersQueryResponse;
use App\Security\Domain\Model\User;
use App\Security\Presentation\Http\Controller\GetUsersController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class GetUsersControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private GetUsersQueryFactory $queryFactory;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->queryFactory = $this->createMock(GetUsersQueryFactory::class);
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
            offset: 0,
            limit: 20,
        );
        $this->queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(
                new GetUsersQuery($request->offset, $request->limit)
            );
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->withAnyParameters()
            ->willReturn(new GetUsersQueryResponse([$user1, $user2]));

        $controller = new GetUsersController($this->queryBus, $this->queryFactory);

        $response = $controller($request);
        $data = json_decode($response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(2, $data->items);
    }
}
