<?php
namespace App\Shared\Presentation\Http\EventListener;
use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\Shared\Presentation\Http\Response\JsonErrorResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
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
            $exception instanceof ValidationFailedException
            => new JsonErrorResponse($exception->getMessage(), 400),

            $exception instanceof IngredientNotFoundException,
                $exception instanceof IngredientTypeNotFoundException
            => new JsonErrorResponse($exception->getMessage(), 404),

            default
            => new JsonErrorResponse($exception->getMessage(), 500),
        };

        $event->setResponse($response);
    }
}
