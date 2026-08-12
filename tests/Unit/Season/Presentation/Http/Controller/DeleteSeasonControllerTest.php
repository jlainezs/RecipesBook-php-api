<?php

namespace App\Tests\Unit\Season\Presentation\Http\Controller;

use App\Season\Presentation\Http\Controller\DeleteSeasonController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class DeleteSeasonControllerTest extends TestCase
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
        $controller = new DeleteSeasonController($commandBus, $validator);
        $request = Request::create(
            uri: '/api/v1/seasons/' . $id,
            method: 'DELETE',
            server: ['Content-Type' => 'application/json']
        );
        $request->attributes->add(['id' => $id]);

        $response = $controller($request);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
