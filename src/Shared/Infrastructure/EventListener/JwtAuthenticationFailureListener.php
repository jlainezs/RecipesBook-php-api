<?php
namespace App\Shared\Infrastructure\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * This class handles JWT authentication failure events
 * to follow RFC9457
 */
final readonly class JwtAuthenticationFailureListener
{
    #[AsEventListener(event: Events::JWT_INVALID)]
    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        $response = $this->createProblemResponse(
            'https://example.com/errors/invalid-token',
            'Invalid JWT Token',
            Response::HTTP_UNAUTHORIZED,
            $event->getException()->getMessage()
            //'El token JWT proporcionat no és vàlid o la signatura és incorrecta.'
        );

        $event->setResponse($response);
    }

    #[AsEventListener(event: Events::JWT_EXPIRED)]
    public function onJWTExpired(JWTExpiredEvent $event): void
    {
        $response = $this->createProblemResponse(
            'https://example.com/errors/expired-token',
            'Expired JWT Token',
            Response::HTTP_UNAUTHORIZED,
            $event->getException()->getMessage()
            //'El token JWT proporcionat ha caducat.'
        );

        $event->setResponse($response);
    }

    #[AsEventListener(event: Events::JWT_NOT_FOUND)]
    public function onJWTNotFound(JWTNotFoundEvent $event): void
    {
        $response = $this->createProblemResponse(
            'https://example.com/errors/missing-token',
            'Missing JWT Token',
            Response::HTTP_UNAUTHORIZED,
            $event->getException()->getMessage()
            //'Cal proporcionar un token d\'autenticació a la capçalera Authorization.'
        );

        $event->setResponse($response);
    }

    #[AsEventListener(event: Events::AUTHENTICATION_FAILURE)]
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event): void
    {
        $response = $this->createProblemResponse(
            'https://example.com/errors/invalid-credentials',
            'Bad credentials',
            Response::HTTP_UNAUTHORIZED,
            $event->getException()->getMessage()
            // 'Invalid username or password'
        );

        $event->setResponse($response);
    }

    private function createProblemResponse(string $type, string $title, int $status, string $detail): JsonResponse
    {
        $data = [
            'type' => $type,
            'title' => $title,
            'status' => $status,
            'detail' => $detail,
        ];

        return new JsonResponse(
            $data,
            $status,
            ['Content-Type' => 'application/problem+json']
        );
    }
}
