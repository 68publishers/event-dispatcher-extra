<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Tests\Cases;

use Tester\Assert;
use Tester\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherFactory;

require __DIR__ . '/../bootstrap.php';

final class EventDispatcherFactoryTestCase extends TestCase
{
	public function testEventDispatcherShouldBeCreated(): void
	{
		$factory = new EventDispatcherFactory(new EventDispatcher());

		Assert::noError(static function () use ($factory) {
			$dispatcher = $factory->create();

			Assert::type(EventDispatcherInterface::class, $dispatcher);
		});
	}
}

(new EventDispatcherFactoryTestCase())->run();
