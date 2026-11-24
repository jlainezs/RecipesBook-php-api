<?php
namespace App\Shared\Infrastructure\Bus\Middleware;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\IsPublicOperation;
use App\Shared\Domain\Exceptions\NoAccessPolicyException;
use BackedEnum;
use ReflectionClass;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final readonly class AuthorizationMiddleware implements MiddlewareInterface
{
    public function __construct(private Security $security)
    {}

    /**
     * @throws NoAccessPolicyException
     * @throws ExceptionInterface
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $reflection = new ReflectionClass($message);
        $attributes = $reflection->getAttributes(IsAuthorized::class);
        $isPublic = $reflection->getAttributes(IsPublicOperation::class);

        if (empty($attributes) && empty($isPublic))
        {
            throw new NoAccessPolicyException($reflection->getShortName());
        }

        foreach ($attributes as $attribute)
        {
            $authorization = $attribute->newInstance();
            $roles = array_map(
                static fn ($r) => $r instanceof BackedEnum ? $r->value : (string) $r,
                $authorization->roles
            );

            $hasAnyRole = false;
            foreach ($roles as $role)
            {
                $hasAnyRole = $this->security->isGranted($role);
                if ($hasAnyRole)
                {
                    break;
                }
            }

            if (!$hasAnyRole)
            {
                throw new AccessDeniedException();
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
