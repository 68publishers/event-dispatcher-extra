<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Tests\Cases;

use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

final class EventDispatcherFactoryTestCase extends TestCase
{
    public function testEventDispatcherShouldBeCreated(): void
    {
        Assert::noError(static function () {
            $factory = new EventDispatcherFactory(new EventDispatcher());
            $dispatcher = $factory->create();

            Assert::type(EventDispatcherInterface::class, $dispatcher);
        });
    }
}

(new EventDispatcherFactoryTestCase())->run();
