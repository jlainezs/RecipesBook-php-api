<?php
namespace App\Shared\Presentation\Http\EventListener;

use App\Shared\Domain\Exceptions\ConflictException;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\Exceptions\EmptyRequiredNameException;
use App\Shared\Domain\Exceptions\EntityNotFoundException;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiErrorsException;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\Response\JsonErrorResponse;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

#[AsEventListener(event: KernelEvents::EXCEPTION, method: 'onKernelException')]
final class ApiExceptionListener
{
    public function __construct(
        #[Autowire('%kernel.debug%')]
        private readonly bool $isDebug
    ){}

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        while ($exception instanceof HandlerFailedException && $exception->getPrevious() !== null) {
            $exception = $exception->getPrevious();
        }

        $statusCode = match (true) {
            $exception instanceof ValidationFailedException,
            $exception instanceof UnprocessableEntityHttpException,
            $exception instanceof InvalidParameterException,
            $exception instanceof EmptyRequiredNameException,
            $exception instanceof EmptyIdNotAllowedException,
            $exception instanceof RequestExceptionInterface
            =>
                Response::HTTP_BAD_REQUEST,

            $exception instanceof EntityNotFoundException
             =>
                 Response::HTTP_NOT_FOUND,

            $exception instanceof ConflictException
            =>
                Response::HTTP_CONFLICT,

            $exception instanceof HttpExceptionInterface
            =>
                $exception->getStatusCode(),

            default
            =>
                Response::HTTP_INTERNAL_SERVER_ERROR,
            };

        if (JsonApi::isJsonApiRequest($event->getRequest())) {
            $event->setResponse(JsonApiResponse::errors($this->jsonApiErrors($exception, $statusCode), $statusCode));

            return;
        }

        $event->setResponse(
            new JsonErrorResponse(
                $exception::class . ': ' . $exception->getMessage(),
                $statusCode,
                [],
                $this->isDebug ? $exception->getFile() : '',
                $this->isDebug ? $exception->getLine() : '',
                $this->isDebug ? $exception->getTraceAsString() : ''
            )
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function jsonApiErrors(Throwable $exception, int $statusCode): array
    {
        if ($exception instanceof JsonApiErrorsException) {
            $errors = $exception->errors;
        } elseif ($exception->getPrevious() instanceof ValidationFailedException) {
            // Raised by #[MapQueryString]: violations refer to query parameters (page.limit -> page[limit]).
            $errors = [];
            foreach ($exception->getPrevious()->getViolations() as $violation) {
                $path = explode('.', $violation->getPropertyPath());
                $parameter = array_shift($path) . implode('', array_map(static fn (string $part): string => '[' . $part . ']', $path));
                $errors[] = JsonApiErrorsException::error($statusCode, $violation->getMessage(), ['parameter' => $parameter]);
            }
        } elseif ($exception instanceof ValidationFailedException) {
            $errors = array_map(
                static fn ($violation): array => JsonApiErrorsException::error($statusCode, $violation->getPropertyPath() . ': ' . $violation->getMessage()),
                iterator_to_array($exception->getViolations())
            );
        } else {
            $detail = $statusCode >= 500 && !$this->isDebug ? 'An unexpected error occurred.' : $exception->getMessage();
            $error = JsonApiErrorsException::error($statusCode, $detail);
            $error['code'] = (new \ReflectionClass($exception))->getShortName();
            $errors = [$error];
        }

        if ($this->isDebug && [] !== $errors) {
            $errors[0]['meta'] = [
                'exception' => $exception::class,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
        }

        return $errors;
    }
}
