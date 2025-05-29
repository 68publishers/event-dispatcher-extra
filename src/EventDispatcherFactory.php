<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final readonly class EventDispatcherFactory implements EventDispatcherFactoryInterface
{
    public function __construct(
        private EventDispatcherInterface $globalEventDispatcher,
    ) {
    }

    public function create(): EventDispatcherInterface
    {
        return new ComposedEventDispatcher(
            globalEventDispatcher: $this->globalEventDispatcher,
            localEventDispatcher: new EventDispatcher(),
        );
    }
}
