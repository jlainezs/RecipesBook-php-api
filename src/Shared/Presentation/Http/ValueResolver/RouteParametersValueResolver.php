<?php
namespace App\Shared\Presentation\Http\ValueResolver;

use App\Shared\Application\Service\ApplicationDataValidator;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[AsTargetedValueResolver]
final readonly class RouteParametersValueResolver implements ValueResolverInterface
{
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

        $params = array_filter(
            $request->attributes->get('_route_params', []),
            static fn (string $key): bool => !str_starts_with($key, '_'),
            ARRAY_FILTER_USE_KEY
        );

        try {
            $dto = $this->denormalizer->denormalize($params, $type, 'csv');
        } catch (ExceptionInterface $e) {
            throw new UnprocessableEntityHttpException($e->getMessage(), $e);
        }

        $this->validator->validate($dto);

        return [$dto];
    }
}
