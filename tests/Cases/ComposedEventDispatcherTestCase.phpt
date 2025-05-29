<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Tests\Cases;

use SixtyEightPublishers\EventDispatcherExtra\ComposedEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

final class ComposedEventDispatcherTestCase extends TestCase
{
    public const string EVENT_NAME = 'test';

    public function testComposedEventDispatcher(): void
    {
        $globalEventDispatcher = new EventDispatcher();

        $globalEventDispatcher->addListener(self::EVENT_NAME, $globalListener1 = $this->createListener('global_listener_1'));
        $globalEventDispatcher->addSubscriber($globalSubscriber = $this->createSubscriber('global_subscriber'));
        $globalEventDispatcher->addListener(self::EVENT_NAME, $globalListener2 = $this->createListener('global_listener_2'));

        $firstComposedEventDispatcher = new ComposedEventDispatcher($globalEventDispatcher, new EventDispatcher());
        $secondComposedEventDispatcher = new ComposedEventDispatcher($globalEventDispatcher, new EventDispatcher());

        $firstComposedEventDispatcher->addListener(self::EVENT_NAME, $firstFooListener = $this->createListener('first.foo_listener'));
        $firstComposedEventDispatcher->addListener(self::EVENT_NAME, $firstBarListener = $this->createListener('first.bar_listener'));
        $firstComposedEventDispatcher->addSubscriber($firstBazSubscriber = $this->createSubscriber('first.baz_subscriber'));

        $secondComposedEventDispatcher->addListener(self::EVENT_NAME, $secondFooListener = $this->createListener('second.foo_listener'));
        $secondComposedEventDispatcher->addSubscriber($secondBarSubscriber = $this->createSubscriber('second.bar_subscriber'));
        $secondComposedEventDispatcher->addListener(self::EVENT_NAME, $secondBazListener = $this->createListener('second.baz_listener'));

        # Test ::getListeners(null)
        Assert::same([
            self::EVENT_NAME => [
                $globalListener1,
                [$globalSubscriber, 'onTest'],
                $globalListener2,
                $firstFooListener,
                $firstBarListener,
                [$firstBazSubscriber, 'onTest'],
            ],
        ], $firstComposedEventDispatcher->getListeners());

        Assert::same([
            self::EVENT_NAME => [
                $globalListener1,
                [$globalSubscriber, 'onTest'],
                $globalListener2,
                $secondFooListener,
                [$secondBarSubscriber, 'onTest'],
                $secondBazListener,
            ],
        ], $secondComposedEventDispatcher->getListeners());

        # Test ::getListeners('test')
        Assert::same([
            $globalListener1,
            [$globalSubscriber, 'onTest'],
            $globalListener2,
            $firstFooListener,
            $firstBarListener,
            [$firstBazSubscriber, 'onTest'],
        ], $firstComposedEventDispatcher->getListeners('test'));

        Assert::same([
            $globalListener1,
            [$globalSubscriber, 'onTest'],
            $globalListener2,
            $secondFooListener,
            [$secondBarSubscriber, 'onTest'],
            $secondBazListener,
        ], $secondComposedEventDispatcher->getListeners('test'));

        # Test ::hasListeners() and ::hasListeners('test')
        Assert::same(true, $firstComposedEventDispatcher->hasListeners());
        Assert::same(true, $secondComposedEventDispatcher->hasListeners());
        Assert::same(true, $firstComposedEventDispatcher->hasListeners(self::EVENT_NAME));
        Assert::same(true, $secondComposedEventDispatcher->hasListeners(self::EVENT_NAME));

        # Test ::dispatch()
        $firstEvent = $this->createEvent();
        $secondEvent = $this->createEvent();

        $firstComposedEventDispatcher->dispatch($firstEvent, self::EVENT_NAME);
        $secondComposedEventDispatcher->dispatch($secondEvent, self::EVENT_NAME);

        Assert::same(['global_listener_1', 'global_subscriber', 'global_listener_2', 'first.foo_listener', 'first.bar_listener', 'first.baz_subscriber'], $firstEvent->getQueue());
        Assert::same(['global_listener_1', 'global_subscriber', 'global_listener_2', 'second.foo_listener', 'second.bar_subscriber', 'second.baz_listener'], $secondEvent->getQueue());

        # Remove listeners and subscribers
        $firstComposedEventDispatcher->removeListener(self::EVENT_NAME, $firstFooListener);
        $firstComposedEventDispatcher->removeSubscriber($firstBazSubscriber);

        $secondComposedEventDispatcher->removeListener(self::EVENT_NAME, $secondFooListener);
        $secondComposedEventDispatcher->removeSubscriber($secondBarSubscriber);

        # Test ::dispatch() again
        $firstEvent = $this->createEvent();
        $secondEvent = $this->createEvent();

        $firstComposedEventDispatcher->dispatch($firstEvent, self::EVENT_NAME);
        $secondComposedEventDispatcher->dispatch($secondEvent, self::EVENT_NAME);

        Assert::same(['global_listener_1', 'global_subscriber', 'global_listener_2', 'first.bar_listener'], $firstEvent->getQueue());
        Assert::same(['global_listener_1', 'global_subscriber', 'global_listener_2', 'second.baz_listener'], $secondEvent->getQueue());
    }

    private function createEvent(): Event
    {
        return new class extends Event {
            private $queue;

            public function add(string $name): void
            {
                $this->queue[] = $name;
            }

            public function getQueue(): array
            {
                return $this->queue;
            }
        };
    }

    private function createListener(string $name): callable
    {
        return static function ($event) use ($name) {
            $event->add($name);
        };
    }

    public function createSubscriber(string $name): EventSubscriberInterface
    {
        return new readonly class($name) implements EventSubscriberInterface {
            public function __construct(
                private string $name,
            ) {
            }

            public static function getSubscribedEvents(): array
            {
                return [
                    ComposedEventDispatcherTestCase::EVENT_NAME => 'onTest',
                ];
            }

            public function onTest($event): void
            {
                $event->add($this->name);
            }
        };
    }
}

(new ComposedEventDispatcherTestCase())->run();
