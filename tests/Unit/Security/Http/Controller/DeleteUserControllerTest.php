<?php
namespace App\Tests\Unit\Security\Http\Controller;

use App\Security\Presentation\Http\Controller\DeleteUserController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class DeleteUserControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_the_request_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $id = AggregateRootId::generateId()->toString();
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();
        $validator->expects($this->once())
            ->method('validate')
            ->withAnyParameters();
        $controller = new DeleteUserController($commandBus, $validator);
        $request = Request::create(
            '/api/v1/users/' . $id,
            'DELETE',
            server: ['Content-Type' => 'application/json']
        );
        $request->attributes->add(['id', $id]);

        $response = $controller($request);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
