<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Tests\Cases;

use Mockery;
use Tester\Assert;
use Tester\TestCase;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherAwareTrait;

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

	/**
	 * {@inheritDoc}
	 */
	protected function tearDown(): void
	{
		parent::tearDown();

		Mockery::close();
	}
}

(new EventDispatcherAwareTraitTestCase())->run();
