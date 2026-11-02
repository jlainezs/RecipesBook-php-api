<?php
namespace App\Shared\Presentation\Http\JsonApi;

use Symfony\Component\HttpFoundation\Request;

final class JsonApi
{
    public const string MEDIA_TYPE = 'application/vnd.api+json';
    public const string VERSION = '1.1';

    /**
     * Route default that marks a route as a JSON:API endpoint:
     * #[Route(..., defaults: [JsonApi::ROUTE_FLAG => true])]
     */
    public const string ROUTE_FLAG = '_jsonapi';

    private function __construct()
    {}

    public static function isJsonApiRequest(?Request $request): bool
    {
        return null !== $request && true === $request->attributes->get(self::ROUTE_FLAG);
    }
}
