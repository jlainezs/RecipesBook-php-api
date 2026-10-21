<?php
namespace App\Shared\Presentation\Http\EventListener;

use App\Shared\Domain\Exceptions\EmptyRequiredNameException;
use App\Shared\Domain\Exceptions\EntityNotFoundException;
use App\Shared\Presentation\Http\Response\JsonErrorResponse;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: KernelEvents::EXCEPTION, method: 'onKernelException')]
final class ApiExceptionListener
{
    public function __construct(
        #[Autowire('%kernel.debug%')]
        private bool $isDebug
    ){}

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HandlerFailedException && $exception->getPrevious() !== null) {
            $exception = $exception->getPrevious();
        }

        $statusCode = match (true) {
            $exception instanceof ValidationFailedException,
            $exception instanceof UnprocessableEntityHttpException,
            $exception instanceof InvalidParameterException,
            $exception instanceof EmptyRequiredNameException
            =>
                Response::HTTP_BAD_REQUEST,

            $exception instanceof EntityNotFoundException
             =>
                 Response::HTTP_NOT_FOUND,

            default
            =>
                Response::HTTP_INTERNAL_SERVER_ERROR,
            };

        $event->setResponse(
            new JsonErrorResponse(
                $exception::class . ': ' . $exception->getMessage(),
                $statusCode,
                [],
                $this->isDebug ? $exception->getFile() : null,
                $this->isDebug ? $exception->getLine() : null,
                $this->isDebug ? $exception->getTraceAsString() : null
            )
        );
    }
}
