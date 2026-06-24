<?php
namespace App\Shared\Presentation\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

final class JsonErrorResponse extends JsonResponse
{
    public function __construct(string $message, int $statusCode, array $headers = [])
    {
        parent::__construct(['error' => $message], $statusCode, $headers);
    }
}
