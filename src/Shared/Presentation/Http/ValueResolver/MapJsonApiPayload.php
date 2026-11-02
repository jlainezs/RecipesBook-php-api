<?php
namespace App\Shared\Presentation\Http\ValueResolver;

use Symfony\Component\HttpKernel\Attribute\ValueResolver;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class MapJsonApiPayload extends ValueResolver
{
    public function __construct(public readonly string $type)
    {
        parent::__construct(JsonApiPayloadValueResolver::class);
    }
}
