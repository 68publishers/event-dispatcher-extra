<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class ComposedEventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private EventDispatcherInterface $globalEventDispatcher,
        private EventDispatcherInterface $localEventDispatcher = new EventDispatcher(),
    ) {
    }

    public function addListener(string $eventName, callable $listener, int $priority = 0): void
    {
        $this->localEventDispatcher->addListener(
            eventName: $eventName,
            listener: $listener,
            priority: $priority,
        );
    }

    public function addSubscriber(EventSubscriberInterface $subscriber): void
    {
        $this->localEventDispatcher->addSubscriber(
            subscriber: $subscriber,
        );
    }

    public function removeListener(string $eventName, callable $listener): void
    {
        $this->localEventDispatcher->removeListener(
            eventName: $eventName,
            listener: $listener,
        );
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber): void
    {
        $this->localEventDispatcher->removeSubscriber(
            subscriber: $subscriber,
        );
    }

    public function getListeners(?string $eventName = null): array
    {
        /** @var ($eventName is null ? array<string, list<callable>> : list<callable>) $global */
        $global = $this->globalEventDispatcher->getListeners(eventName: $eventName);
        /** @var ($eventName is null ? array<string, list<callable>> : list<callable>) $local */
        $local = $this->localEventDispatcher->getListeners(eventName: $eventName);

        if (null !== $eventName) {
            return array_merge($global, $local);
        }

        /**
         * @var string         $name
         * @var list<callable> $events
         */
        foreach ($global as $name => $events) {
            if (isset($local[$name])) {
                /** @var list<callable> $localEvents */
                $localEvents = $local[$name];
                $global[$name] = array_merge($events, $localEvents);
            }
        }

        return $global;
    }

    public function getListenerPriority(string $eventName, callable $listener): ?int
    {
        return $this->globalEventDispatcher->getListenerPriority(
            eventName: $eventName,
            listener: $listener,
        ) ?? $this->localEventDispatcher->getListenerPriority(
            eventName: $eventName,
            listener: $listener,
        );
    }

    public function hasListeners(?string $eventName = null): bool
    {
        return $this->globalEventDispatcher->hasListeners(eventName: $eventName) || $this->localEventDispatcher->hasListeners(eventName: $eventName);
    }

    public function dispatch(object $event, ?string $eventName = null): object
    {
        $this->globalEventDispatcher->dispatch(
            event: $event,
            eventName: $eventName,
        );
        $this->localEventDispatcher->dispatch(
            event: $event,
            eventName: $eventName,
        );

        return $event;
    }
}
