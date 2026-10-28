<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Tests\Cases;

use Mockery;
use RuntimeException;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherAwareTrait;
use SixtyEightPublishers\EventDispatcherExtra\Tests\Fixture\DummyService;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

final class EventDispatcherAwareTraitTestCase extends TestCase
{
    public function testEventDispatcherShouldBeReturnedWhenExists(): void
    {
        $eventDispatcher = new EventDispatcher();
        $eventDispatcherAwareTrait = Mockery::mock(EventDispatcherAwareTrait::class);

        $eventDispatcherAwareTrait->setEventDispatcher($eventDispatcher);

        Assert::noError(static function () use ($eventDispatcherAwareTrait, $eventDispatcher) {
            Assert::same($eventDispatcher, $eventDispatcherAwareTrait->getEventDispatcher());
        });
    }

    public function testExceptionShouldBeThrownWhenEventDispatcherIsMissing(): void
    {
        $eventDispatcherAwareTrait = Mockery::mock(EventDispatcherAwareTrait::class);

        Assert::throws(static function () use ($eventDispatcherAwareTrait) {
            $eventDispatcherAwareTrait->getEventDispatcher();
        }, RuntimeException::class);
    }

    public function testListenerShouldReceiveDispatchedEvent(): void
    {
        $service = new DummyService();
        $service->setEventDispatcher(new EventDispatcher());
        $event = new stdClass();
        $received = [];

        $service->addEventListener('dummy', static function (object $e) use (&$received): void {
            $received[] = $e;
        });

        Assert::same($event, $service->dispatch($event));
        Assert::same([$event], $received);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }
}

(new EventDispatcherAwareTraitTestCase())->run();
