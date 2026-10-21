<?php
namespace App\Shared\Presentation\Http\EventListener;

use App\Shared\Domain\Exceptions\EmptyRequiredNameException;
use App\Shared\Domain\Exceptions\EntityNotFoundException;
use App\Shared\Presentation\Http\Response\JsonErrorResponse;
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
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HandlerFailedException && $exception->getPrevious() !== null) {
            $exception = $exception->getPrevious();
        }

        $response = match (true) {
            $exception instanceof ValidationFailedException,
            $exception instanceof UnprocessableEntityHttpException,
            $exception instanceof InvalidParameterException,
            $exception instanceof EmptyRequiredNameException
            => new JsonErrorResponse(
                    $exception->getMessage(),
                    Response::HTTP_BAD_REQUEST, [],
                    $exception->getFile(),
                    $exception->getLine()
                ),

            $exception instanceof EntityNotFoundException
             => new JsonErrorResponse(
                 $exception->getMessage(),
                 Response::HTTP_NOT_FOUND
            ),

            default
            => new JsonErrorResponse(
                    $exception::class . ': ' . $exception->getMessage(),
                    Response::HTTP_INTERNAL_SERVER_ERROR, [],
                    $exception->getFile(),
                    $exception->getLine(),
                    $exception->getTraceAsString()
                )
            };

        $event->setResponse($response);
    }
}
