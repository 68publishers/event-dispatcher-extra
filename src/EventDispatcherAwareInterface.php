<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface EventDispatcherAwareInterface
{
	/**
	 * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
	 *
	 * @return void
	 */
	public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void;

	/**
	 * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
	 */
	public function getEventDispatcher(): EventDispatcherInterface;
}
