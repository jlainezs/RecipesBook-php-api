<?php
namespace App\Tests\Unit\Security\Preserntation\Http\Controller;

use App\Security\Application\Query\User\GetUser\GetUserQuery;
use App\Security\Application\Query\User\GetUser\GetUserQueryFactory;
use App\Security\Application\Query\User\GetUser\GetUserQueryResponse;
use App\Security\Application\Query\User\GetUser\GetUserRouteDto;
use App\Security\Application\Query\User\UserDto;
use App\Security\Domain\Model\User;
use App\Security\Presentation\Http\Controller\GetUserController;
use App\Security\Presentation\Http\Resource\UserResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetUserControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $queryFactory = $this->createMock(GetUserQueryFactory::class);
        $user = User::create(
            AggregateRootId::generateId()->toString(),
            'eml@eml.com',
            'password',
            'first name',
            'last name',
            []
        );

        $queryFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(
                new GetUserQuery($user->getId()->toString())
            );

        $queryBus->expects($this->once())
            ->method('ask')
            ->withAnyParameters()
            ->willReturn(new GetUserQueryResponse(
                new UserDto(
                    $user->getId()->toString(),
                    $user->getEmail()->value(),
                    $user->getFirstName()->value(),
                    $user->getLastName()->value(),
                    $user->getRoles(),
                    $user->getCreatedAt(),
                    $user->getUpdatedAt()
                )
            ));

        $controller = new GetUserController(
            $queryBus,
            $queryFactory,
            new UserResource($this->createStub(UrlGeneratorInterface::class))
        );
        $request = new GetUserRouteDto($user->getId()->toString());

        $response = $controller($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
