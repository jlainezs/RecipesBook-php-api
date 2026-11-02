<?php
namespace App\Shared\Presentation\Http\JsonApi;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

/**
 * Carries ready-made JSON:API error objects (with their "source") up to the exception listener.
 */
final class JsonApiErrorsException extends HttpException
{
    /**
     * @param array<int, array<string, mixed>> $errors
     */
    public function __construct(
        int $statusCode,
        public readonly array $errors,
        ?Throwable $previous = null
    )
    {
        parent::__construct($statusCode, implode("\n", array_column($errors, 'detail')), $previous);
    }

    public static function fromViolations(ConstraintViolationListInterface $violations, string $pointerPrefix, ?Throwable $previous = null): self
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = self::error(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $violation->getMessage(),
                ['pointer' => $pointerPrefix . '/' . str_replace('.', '/', $violation->getPropertyPath())]
            );
        }

        return new self(Response::HTTP_UNPROCESSABLE_ENTITY, $errors, $previous);
    }

    public static function single(int $statusCode, string $detail, array $source = [], ?Throwable $previous = null): self
    {
        return new self($statusCode, [self::error($statusCode, $detail, $source)], $previous);
    }

    public static function error(int $statusCode, string $detail, array $source = []): array
    {
        $error = [
            'status' => (string) $statusCode,
            'title' => Response::$statusTexts[$statusCode] ?? 'Error',
            'detail' => $detail,
        ];
        if ([] !== $source) {
            $error['source'] = $source;
        }

        return $error;
    }
}
