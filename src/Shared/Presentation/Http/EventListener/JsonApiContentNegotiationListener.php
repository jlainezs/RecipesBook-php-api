<?php
namespace App\Shared\Presentation\Http\EventListener;

use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiErrorsException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\AcceptHeaderItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Enforces JSON:API content negotiation and query parameter rules on routes flagged with JsonApi::ROUTE_FLAG.
 * Runs after the router (32) so the route flag is available, and before the firewall (8).
 */
#[AsEventListener(event: KernelEvents::REQUEST, method: 'onKernelRequest', priority: 16)]
final class JsonApiContentNegotiationListener
{
    private const array ALLOWED_MEDIA_TYPE_PARAMETERS = ['ext', 'profile'];
    private const array SUPPORTED_QUERY_PARAMETERS = ['page'];
    private const array UNSUPPORTED_QUERY_PARAMETERS = ['include', 'fields', 'sort', 'filter'];

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$event->isMainRequest() || !JsonApi::isJsonApiRequest($request)) {
            return;
        }

        if ('' !== $request->getContent() && !$this->isJsonApiMediaType(AcceptHeaderItem::fromString($request->headers->get('Content-Type')))) {
            throw JsonApiErrorsException::single(
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
                sprintf('Request documents must be sent with Content-Type "%s".', JsonApi::MEDIA_TYPE),
                ['header' => 'Content-Type']
            );
        }

        $jsonApiAcceptItems = array_filter(
            AcceptHeader::fromString($request->headers->get('Accept'))->all(),
            static fn (AcceptHeaderItem $item): bool => JsonApi::MEDIA_TYPE === strtolower($item->getValue())
        );
        if ([] !== $jsonApiAcceptItems && [] === array_filter($jsonApiAcceptItems, $this->isJsonApiMediaType(...))) {
            throw JsonApiErrorsException::single(
                Response::HTTP_NOT_ACCEPTABLE,
                sprintf('"%s" may only be qualified with the "ext" and "profile" parameters.', JsonApi::MEDIA_TYPE),
                ['header' => 'Accept']
            );
        }

        foreach (array_keys($request->query->all()) as $name) {
            $name = (string) $name;
            if (in_array($name, self::UNSUPPORTED_QUERY_PARAMETERS, true)) {
                throw JsonApiErrorsException::single(
                    Response::HTTP_BAD_REQUEST,
                    sprintf('The query parameter "%s" is not supported by this endpoint.', $name),
                    ['parameter' => $name]
                );
            }
            // Implementation-specific query parameters must contain at least one non a-z character.
            if (preg_match('/^[a-z]+$/', $name) && !in_array($name, self::SUPPORTED_QUERY_PARAMETERS, true)) {
                throw JsonApiErrorsException::single(
                    Response::HTTP_BAD_REQUEST,
                    sprintf('The query parameter "%s" is not a valid JSON:API query parameter.', $name),
                    ['parameter' => $name]
                );
            }
        }
    }

    private function isJsonApiMediaType(AcceptHeaderItem $item): bool
    {
        return JsonApi::MEDIA_TYPE === strtolower($item->getValue())
            && [] === array_diff(array_keys($item->getAttributes()), self::ALLOWED_MEDIA_TYPE_PARAMETERS);
    }
}
