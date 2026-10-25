<?php
namespace App\Shared\Presentation\Http\ValueResolver;

use Symfony\Component\HttpKernel\Attribute\ValueResolver;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class MapRouteParameter extends ValueResolver
{
    public function __construct()
    {
        parent::__construct(RouteParametersValueResolver::class);
    }
}
