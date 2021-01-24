<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface EventDispatcherFactoryInterface
{
	/**
	 * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
	 */
	public function create(): EventDispatcherInterface;
}
