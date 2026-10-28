<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Tests\Fixture;

interface DummyServiceFactoryInterface
{
    public function create(): DummyService;
}
