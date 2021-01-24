<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Tests\Fixture;

use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherAwareTrait;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherAwareInterface;

final class DummyService implements EventDispatcherAwareInterface
{
	use EventDispatcherAwareTrait;
}
