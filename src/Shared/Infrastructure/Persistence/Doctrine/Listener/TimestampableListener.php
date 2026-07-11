<?php
namespace App\Shared\Infrastructure\Persistence\Doctrine\Listener;

use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use ReflectionClass;

#[AsDoctrineListener(event: Events::preUpdate)]
final class TimestampableListener
{
    private function changeUpdateTime(object $entity): void
    {
        $reflectionClass = new ReflectionClass($entity);

        if ($reflectionClass->hasProperty('updatedAt')) {
            $property = $reflectionClass->getProperty('updatedAt');
            $property->setValue($entity, new DateTimeImmutable());
        }
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->changeUpdateTime($args->getObject());
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $this->changeUpdateTime($args->getObject());
    }
}
