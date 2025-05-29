<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Tests\Fixture;

use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherAwareInterface;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherAwareTrait;

final class DummyService implements EventDispatcherAwareInterface
{
    use EventDispatcherAwareTrait;
}
