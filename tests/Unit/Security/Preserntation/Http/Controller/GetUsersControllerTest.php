<?php
namespace App\Tests\Unit\Security\Preserntation\Http\Controller;

use App\Security\Application\Query\User\GetUsers\GetUsersDto;
use App\Security\Application\Query\User\GetUsers\GetUsersQuery;
use App\Security\Application\Query\User\GetUsers\GetUsersQueryResponse;
use App\Security\Domain\Model\User;
use App\Security\Presentation\Http\Controller\GetUsersController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetUsersControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private ApplicationDataValidator $validator;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    #[Test]
    public function it_returns_users_list(): void
    {
        $user1 = User::create(
            'eml1@eml.com',
            'password1',
            'first name 1',
            'last name 1',
            []
        );
        $user2 = User::create(
            'eml2@eml.com',
            'password2',
            'first name2',
            'last name2',
            []
        );
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->withAnyParameters()
            ->willReturn(new GetUsersQueryResponse([$user1, $user2]));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (GetUsersQuery $query) => $query->offet >= 0 && $query->limit > 10
            ));

        $controller = new GetUsersController($this->queryBus, $this->validator);
        $request = new GetUsersDto(
            offset: 0,
            limit: 20,
        );
        $response = $controller($request);
        $data = json_decode($response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $data->items);
    }
}
