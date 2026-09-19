<?php
namespace App\Tests\Unit\Security\Preserntation\Http\Controller;

use App\Security\Application\Query\User\GetUser\GetUserQuery;
use App\Security\Domain\Model\User;
use App\Security\Presentation\Http\Controller\GetUserController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetUserControllerTest extends TestCase
{
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $user = User::create(
            'eml@eml.com',
            'password',
            'first name',
            'last name',
            []
        );

        $queryBus->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                fn(GetUserQuery $query) => $query->id === $user->getId()->toString()
            ))->willReturn($user);

        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn(GetUserQuery $query) =>  $query->id === $user->getId()->toString()
            ));
        $controller = new GetUserController($queryBus, $validator);

        $response = $controller($user->getId()->toString());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
