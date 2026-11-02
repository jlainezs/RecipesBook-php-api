<?php
namespace App\Shared\Presentation\Http\JsonApi;

use Symfony\Component\HttpFoundation\JsonResponse;

final class JsonApiResponse extends JsonResponse
{
    public function __construct(array $document, int $status = 200, array $headers = [])
    {
        $headers['Content-Type'] = JsonApi::MEDIA_TYPE;
        parent::__construct(['jsonapi' => ['version' => JsonApi::VERSION]] + $document, $status, $headers);
    }

    /**
     * @param array<int, array<string, mixed>> $errors JSON:API error objects
     */
    public static function errors(array $errors, int $status, array $headers = []): self
    {
        return new self(['errors' => $errors], $status, $headers);
    }
}
