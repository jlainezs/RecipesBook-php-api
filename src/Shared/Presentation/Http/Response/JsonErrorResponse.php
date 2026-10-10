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
        $headers['content-type'] = 'application/problem+json';
        $instance = $file . ':' . $line;
        $details = explode("\n", $traceAsString);
        parent::__construct([
            'type' => $message,
            'title' => $message,
            'status' => $statusCode,
            'instance' => $instance,
            'detail' => $details
        ], $statusCode, $headers);
    }
}
