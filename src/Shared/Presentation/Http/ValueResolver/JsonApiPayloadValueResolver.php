<?php
namespace App\Shared\Presentation\Http\ValueResolver;

use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Presentation\Http\JsonApi\JsonApiErrorsException;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * Unwraps a JSON:API request document ({"data": {"type", "id", "attributes"}})
 * and maps its attributes into the argument DTO.
 */
#[AsTargetedValueResolver]
final readonly class JsonApiPayloadValueResolver implements ValueResolverInterface
{
    private const string ATTRIBUTES_POINTER = '/data/attributes';

    public function __construct(
        private DenormalizerInterface $denormalizer,
        private ApplicationDataValidator $validator
    ){}

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();
        if (null === $type || !class_exists($type)) {
            throw new LogicException(sprintf('Argument "$%s" must be typed with a DTO class.', $argument->getName()));
        }

        $attribute = $argument->getAttributesOfType(MapJsonApiPayload::class)[0];
        $data = $request->toArray()['data'] ?? null;

        if (!is_array($data)) {
            throw JsonApiErrorsException::single(Response::HTTP_BAD_REQUEST, 'The request document must contain a resource object as primary data.', ['pointer' => '/data']);
        }
        if (!isset($data['type'])) {
            throw JsonApiErrorsException::single(Response::HTTP_BAD_REQUEST, 'The resource object must contain a "type" member.', ['pointer' => '/data']);
        }
        if ($data['type'] !== $attribute->type) {
            throw JsonApiErrorsException::single(
                Response::HTTP_CONFLICT,
                sprintf('Resource type "%s" does not match the endpoint type "%s".', $data['type'], $attribute->type),
                ['pointer' => '/data/type']
            );
        }

        $routeId = $request->attributes->get('id');
        if (null === $routeId && isset($data['id'])) {
            throw JsonApiErrorsException::single(Response::HTTP_FORBIDDEN, 'Client-generated ids are not supported.', ['pointer' => '/data/id']);
        }
        if (null !== $routeId && !isset($data['id'])) {
            throw JsonApiErrorsException::single(Response::HTTP_BAD_REQUEST, 'The resource object must contain an "id" member.', ['pointer' => '/data']);
        }
        if (null !== $routeId && $data['id'] !== $routeId) {
            throw JsonApiErrorsException::single(Response::HTTP_CONFLICT, 'Resource id does not match the endpoint id.', ['pointer' => '/data/id']);
        }

        $attributes = $data['attributes'] ?? [];
        if (!is_array($attributes)) {
            throw JsonApiErrorsException::single(Response::HTTP_BAD_REQUEST, 'The "attributes" member must be an object.', ['pointer' => self::ATTRIBUTES_POINTER]);
        }

        try {
            $dto = $this->denormalizer->denormalize($attributes, $type);
        } catch (MissingConstructorArgumentsException $e) {
            throw new JsonApiErrorsException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                array_map(
                    static fn (string $name): array => JsonApiErrorsException::error(
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        sprintf('The attribute "%s" is required.', $name),
                        ['pointer' => self::ATTRIBUTES_POINTER . '/' . $name]
                    ),
                    $e->getMissingConstructorArguments()
                ),
                $e
            );
        } catch (NotNormalizableValueException $e) {
            $source = null !== $e->getPath() ? ['pointer' => self::ATTRIBUTES_POINTER . '/' . str_replace('.', '/', $e->getPath())] : [];
            throw JsonApiErrorsException::single(Response::HTTP_UNPROCESSABLE_ENTITY, $e->getMessage(), $source, $e);
        } catch (ExceptionInterface $e) {
            throw JsonApiErrorsException::single(Response::HTTP_UNPROCESSABLE_ENTITY, $e->getMessage(), [], $e);
        }

        try {
            $this->validator->validate($dto);
        } catch (ValidationFailedException $e) {
            throw JsonApiErrorsException::fromViolations($e->getViolations(), self::ATTRIBUTES_POINTER, $e);
        }

        return [$dto];
    }
}
