<?php
namespace App\Shared\Presentation\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

final class JsonErrorResponse extends JsonResponse
{
    public function __construct(
        string $message,
        int $statusCode,
        array $headers = [],
        string $file = '',
        string $line = '',
        string $traceAsString = '',
    )
    {
        parent::__construct([
            'error' => $message,
            'file' => $file,
            'line' => $line,
            'trace' => $traceAsString,
        ], $statusCode, $headers);
    }
}
