<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra;

use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherAwareTrait
{
    protected ?EventDispatcherInterface $eventDispatcher = null;

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        if (null === $this->eventDispatcher) {
            throw new RuntimeException(sprintf(
                'Event dispatcher is not set, please use method %s::setEventDispatcher().',
                static::class,
            ));
        }

        return $this->eventDispatcher;
    }

    public function addEventListener(string $eventName, callable $listener, int $priority = 0): void
    {
        $this->getEventDispatcher()->addListener(
            eventName: $eventName,
            listener: $listener,
            priority: $priority,
        );
    }

    protected function dispatchEvent(object $event, ?string $eventName = null): object
    {
        return $this->getEventDispatcher()->dispatch(
            event: $event,
            eventName: $eventName,
        );
    }
}
